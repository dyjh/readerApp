<?php

namespace App\Repositories\store;

use App\Models\abstracts\AbstractPayable;
use App\Models\stores\OrderItem;
use App\Services\store\Exceptions\OrderGeneratorException;
use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\stores\Order;
use App\Services\pay\PayService;


/**
 * Class OrderRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories\Store;
 */
class OrderRepositoryRepositoryEloquent extends BaseRepository implements OrderRepositoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Order::class;
    }

    public function refundOrder(array $request) :void
    {
        $items = OrderItem::whereIn('id', $request['items'])->get();

        throw_if(($items->count()==0), OrderGeneratorException::class, 40017);

        unset($request['items']);
        $request['statement'] = 3;
        $request['refund_request_at'] = Carbon::now();

        $items->each(function (OrderItem $item) use ($request){
            throw_if(!in_array($item->statement,[1, 2]), OrderGeneratorException::class, 40017, $item->product_name.' 该商品状态不能退款。');
            $item->update($request);
        });
    }

    public function pay(AbstractPayable $model, string $type)
    {
        $payService = new PayService($model, $type);

        $order = $payService->getPayOrder();

        $request = $payService->getPayConfig('order');

        $result = $request->app($order);

        $payService->created($result);

        return ['content' => $result->getContent()];
    }

    public function alipayNotify($data)
    {
        $this->updateOrder($data->out_trade_no, $data->trade_no);
    }

    public function wxpayNotify($data)
    {
        $this->updateOrder($data->out_trade_no, $data->transaction_id);
    }

    protected function updateOrder($out_trade_no, $trans_no)
    {
        $order = $this->model()::where(['trade_no'=> $out_trade_no, 'statement'=> 1])->firstOrFail();

        $order->update(['trans_no'=> $trans_no, 'paid_at'=> Carbon::now(), 'statement' => 2]);

        $order->orderItems()->update(['statement'=> 1]);

        $order->receipt()->latest()->update(['paid_at'=> Carbon::now(), 'paid'=> true]);
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
