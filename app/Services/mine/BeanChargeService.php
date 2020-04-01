<?php


namespace App\Services\mine;


use App\Common\Definition;
use App\Models\baseinfo\BeanRecord;
use App\Models\baseinfo\ProductBean;
use App\Models\baseinfo\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Yansongda\Pay\Pay;

class BeanChargeService
{
    public function chargeRequest(Student $student, ProductBean $productBeanModel, string $paymethod)
    {
        $payement = $this->payment($paymethod);

        $tradeNo = $this->generateTradeNumber();
        $record = $student->beanRecords()->create([
            'changed_by' => Definition::BOOK_BEAN_CHANGE_BY_CHARGE,
            'amount' => $productBeanModel->getAttribute('amount'),
            'payment_method' => $paymethod,
            'trade_no' => $tradeNo
        ]);

        $fee = $productBeanModel->getAttribute('price');
        $desc = "充值 {$productBeanModel->getAttribute('amount')} 书豆";

        if ($paymethod == 'alipay') {
            $order = [
                'out_trade_no' => $tradeNo,
                'subject' => $desc,
                'total_amount' => $fee,
            ];

        } else {
            $order = [
                'out_trade_no' => $tradeNo,
                'body' => $desc,
                'total_fee' => $fee * 100,
            ];
        }

        /** @var Response $response */

        $response = $payement->app($order);
        if ($response->getStatusCode() != 200) {
            Log::error($response);
        }

        return [
            'record' => $record,
            'payment' => $response->getContent()
        ];
    }

    public function paidNotify(string $paymethod)
    {
        $payment = $this->payment($paymethod);
        try {
            $data = $payment->verify();;

            if (!in_array($data->get('trade_status'), ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
                $tradeNo = $data->get('out_trade_no');
                $transNo = $data->get('trade_no');

                // @todo: async
                $this->handleCharge($tradeNo, $transNo, $paymethod);
                return $payment->success();
            } else {
                \Yansongda\Pay\Log::error('[paidNotify@BeanChargeService] ', $data->all());
            }
        } catch (\Exception $e) {
            // $e->getMessage();
            Log::error("[paidNotify@BeanChargeService] ");
        }

        return $payment->success();
    }

    /**
     * @param string $paymethod
     * @return \Yansongda\Pay\Gateways\Alipay|\Yansongda\Pay\Gateways\Wechat
     * @author marhone
     */
    protected function payment(string $paymethod)
    {
        if ($paymethod == 'alipay') {
            $config = [
                'notify_url' => route('bean-ali-notify'),
                'ali_public_key' => config('pay.alipay.ali_public_key'),
                'private_key' => config('pay.alipay.private_key'),
                'app_id' => config('pay.alipay.app_id'),
                'log' => [
                    'file' => storage_path('logs/alipay.log'),
                ],
                //    'timeout_express' => $model->getExpiredMinutes() . 'm',
            ];

            $payment = Pay::alipay($config);
        } else {
            $config = [
                'app_id' => config('pay.wechat.app_id'),
                'mch_id' => config('pay.wechat.mch_id'),
                'key' => config('pay.wechat.key'),
                'cert_client' => './apiclient_cert.pem',
                'cert_key' => './apiclient_key.pem',
                'notify_url' => route('bean-wx-notify'),
                'log' => [
                    'file' => storage_path('logs/wechat_pay.log'),
                ],
                //    'time_expire' =>$model->getExpiredAt()->format('YmdHis')
            ];
            $payment = Pay::wechat($config);
        }

        return $payment;
    }

    protected function handleCharge(string $tradeNumber, string $transNumber, string $paymethod)
    {
        DB::beginTransaction();
        try {
            /** @var BeanRecord $record */
            $record = BeanRecord
                ::with(['owner'])
                ->where([
                    'trade_no' => $tradeNumber
                ])
                ->first();
            if (!$record) {
                throw new \Exception('书豆充值记录为找到!');
            }
            /** @var Student $owner */
            $owner = $record->owner;

            $delta = $record->getAttribute('amount');
            $before = $owner->getAttribute('total_beans');
            $after = $before + $delta;
            $owner->setAttribute('total_beans', $after);
            $record->update([
                'changed_at' => Carbon::now()->toDateTimeString(),
                'payment_method' => $paymethod,
                'trans_no' => $transNumber,
                'before_beans_total' => $before,
                'after_beans_total' => $after,
            ]);
            $owner->update([
                'total_beans' => $after
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception("{$exception->getMessage()}");
        }
    }

    protected function generateTradeNumber(): string
    {
        return date('YmdHis') . str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT);
    }
}