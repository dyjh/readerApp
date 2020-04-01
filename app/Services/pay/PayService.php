<?php

namespace App\Services\pay;

use App\Models\abstracts\AbstractPayable;
use App\Models\baseinfo\Receipt;
use Carbon\Carbon;
use App;
use Ramsey\Uuid\Uuid;

class PayService
{
    protected $uuid;

    protected $gateway;

    protected $openid;

    protected $platform;

    protected $type = 'app';

    /**
     * @var AbstractPayable
     */
    protected $model;

    /**
     * @var Receipt
     */
    protected $receipt;

    public function __construct(AbstractPayable $model, $platform = 'alipay', $type = 'app')
    {
        $uuid = Uuid::uuid4();
        $this->uuid = $uuid->toString();

        $this->model = $model;

        $this->platform = $platform;

        $this->type = $type;
    }

    public function created($result)
    {
        $data = [
            'platform'   => $this->platform,
            'title'      => $this->model->getTitle(),
            'type'       => $this->type,
            'total'      => $this->model->getTotal(),
            'student_id' => $this->model->getStudentId(),
            'expired_at' => $this->model->getExpiredAt()
        ];

        $data['original'] = $result->getContent();

        return $this->model->receipts()->create($data);
    }

    public function getPayOrder()
    {
        if ($this->platform == 'alipay') {

            $order = [
                'out_trade_no' => $this->model->getTradeNo(),
                'subject'      => $this->model->getTitle(),
                'total_amount' => $this->model->getTotal(),
            ];

        } else{

            $order = [
                'out_trade_no' => $this->model->getTradeNo(),
                'body'         => $this->model->getTitle(),
                'total_fee'    => $this->model->getTotal() * 100,
            ];
        }

        return $order;
    }

    public function getPayConfig(string $type = 'order')
    {
        return (new OrderConfig($this->platform))->{$type}();
    }

    public function refund(Receipt $receipt)
    {
        $this->receipt = $receipt;

        $this->platform = $receipt->platform;

        $this->type = $receipt->type;

        $request = $this->makeRefundRequest();

        $response = $request->send();

        return $this->refunded($response);
    }

    protected function refunded($response)
    {
        $responseData = $response->getData();

        logger($responseData);

        $data = [
            'id' => $this->uuid,
            'refunded_at' => Carbon::now()
        ];

        if ($this->platform == 'alipay') {
            $data['refund_id'] = $responseData['alipay_trade_refund_response']['trade_no'] ?? 123456;
            $data['money'] = $responseData['alipay_trade_refund_response']['refund_fee'];
            $data['refunded_at'] = Carbon::parse($responseData['alipay_trade_refund_response']['gmt_refund_pay']);
        } elseif ($this->platform == 'wxpay') {
            $data['refund_id'] = $responseData['refund_id'];
            $data['money'] = $responseData['total_fee'] / 100;
        }

        $refund = $this->receipt->refunds()->create($data);

        event(new ReceiptRefunded($this->receipt));

        return $refund;
    }

    protected function makeRefundRequest()
    {
        $money = App::environment() === 'production' ? $this->receipt->money : 0.01;

        if ($this->platform == 'alipay') {
            $content = [
                'trade_no' => $this->receipt->trade_no,
                'refund_amount' => $money,
                'out_request_no' => $this->uuid
            ];

            $request = $this->gateway->refund()
                ->setBizContent($content)
                ->setAlipaySdk(null)
                ->setTestMode(null)
                ->setReturnUrl(url()->route('alipay_return'));
        } elseif ($this->platform == 'wxpay') {
            $request = $this->gateway->refund([
                'out_trade_no' => (new ShortUuid())->encode(Uuid::fromString($this->receipt->id)),
                'out_refund_no' => $this->uuid,
                'total_fee' => $money * 100,
                'refund_fee' => $money * 100,
            ]);
        }

        return $request;
    }
}
