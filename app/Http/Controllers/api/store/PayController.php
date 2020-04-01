<?php

namespace App\Http\Controllers\api\store;

use App\Http\Requests\store\PayOrder;
use App\Models\stores\Order;
use App\Repositories\store\OrderRepositoryRepositoryEloquent;
use App\Services\pay\OrderConfig;
use App\Services\store\Exceptions\OrderGeneratorException;
use DB;

class PayController extends StoreController
{

    private $repository;

    public function __construct(OrderRepositoryRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 支付
     */
    public function pay(Order $order, PayOrder $request)
    {
        throw_if(($order->statement !=1), OrderGeneratorException::class, 40005);

        $pay_type = $request->input('platform', 'alipay');
        $data = $this->repository->pay($order, $pay_type);

        return $this->json($data);
    }

    /**
     * 支付宝
     */
    public function ali_notify_url()
    {
        $alipay = (new OrderConfig('alipay'))->order();

        DB::beginTransaction();
        try{
            $data = $alipay->verify();

            if(!in_array($data->trade_status, ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
                return $alipay->success();
            }

            $this->repository->alipayNotify($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            \Yansongda\Pay\Log::error($e->getMessage());
        }

        return;
    }

    /**
     * 微信
     */
    public function wx_return_url()
    {
        $wxpay = (new OrderConfig('wxpay'))->order();

        DB::beginTransaction();
        try{
            $data = $wxpay->verify();

            $this->repository->wxpayNotify($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            \Yansongda\Pay\Log::error($e->getMessage());
        }

        return;
    }
}
