<?php

namespace App\Services\pay;

use Yansongda\Pay\Pay;

class OrderConfig
{
    protected $platform;

    public function __construct($platform = 'alipay')
    {
        $this->platform = $platform;
    }

    public function order()
    {
        if ($this->platform == 'alipay') {
            $config = [
                'notify_url'        => route('order-ali-notify'),
                'return_url'        => route('order-ali-return'),
                'ali_public_key'    => config('pay.alipay.ali_public_key'),
                'private_key'       => config('pay.alipay.private_key'),
                'app_id'            => config('pay.alipay.app_id'),
                'log'               => [
                    'file'          => storage_path('logs/alipay.log'),
                ],
                //    'timeout_express' => $model->getExpiredMinutes() . 'm',
            ];

            $request =  Pay::alipay($config);

        } else {
            $config = [
                'app_id'        => config('pay.wechat.app_id'),
                'mch_id'        => config('pay.wechat.mch_id'),
                'key'           => config('pay.wechat.key'),
                'cert_client'   => './apiclient_cert.pem',
                'cert_key'      => './apiclient_key.pem',
                'return_url'    => route('order-wx-return'),
                'notify_url'    => route('order-wx-notify'),
                'log'           => [
                    'file'      => storage_path('logs/wechat_pay.log'),
                ],
                //    'time_expire' =>$model->getExpiredAt()->format('YmdHis')
            ];
            $request =  Pay::wechat($config);
        }

        return $request;
    }

    public function lesson()
    {
        if ($this->platform == 'alipay') {
            $config = [
                'notify_url'        => route('lesson-ali-notify'),
                'return_url'        => route('lesson-ali-return'),
                'ali_public_key'    => config('pay.alipay.ali_public_key'),
                'private_key'       => config('pay.alipay.private_key'),
                'app_id'            => config('pay.alipay.app_id'),
                'log'               => [
                    'file'          => storage_path('logs/alipay.log'),
                ],
                //    'timeout_express' => $model->getExpiredMinutes() . 'm',
            ];

            $request =  Pay::alipay($config);

        } else {
            $config = [
                'app_id'        => config('pay.wechat.app_id'),
                'mch_id'        => config('pay.wechat.mch_id'),
                'key'           => config('pay.wechat.key'),
                'cert_client'   => './apiclient_cert.pem',
                'cert_key'      => './apiclient_key.pem',
                'return_url'    => route('lesson-wx-return'),
                'notify_url'    => route('lesson-wx-notify'),
                'log'           => [
                    'file'      => storage_path('logs/wechat_pay.log'),
                ],
                //    'time_expire' =>$model->getExpiredAt()->format('YmdHis')
            ];
            $request =  Pay::wechat($config);
        }

        return $request;
    }

}
