<?php


namespace App\Services\common;

use App\Exceptions\api\SMSCodeExpiredException;
use Illuminate\Support\Facades\Redis;

class SMSNotificationServices
{
    /**
     * 校验短信验证码
     * @param $phone
     * @param $sms_code
     * @return bool
     * @throws SMSCodeExpiredException
     * @author marhone
     */
    public function smsVerifier($phone, $sms_code): bool
    {
        $key = "{$phone}-register";
        $code = Redis::get($key);

        if (is_null($code)) {
            throw new SMSCodeExpiredException("短信验证码已失效");
        }
        Redis::del($key);

        return $code == $sms_code;
    }

    /**
     * 发送短信
     * @param string $phone
     * @author marhone
     * @todo: 接入短信接口
     */
    public function smsSendBindingCode(string $phone)
    {
        $key = "{$phone}-register";
        $code = $this->smsCodeGenerator();
        Redis::set($key, $code);
        Redis::expire($key, 300);
        \Log::notice("[SMS] {$phone} {$code}");

        return $code;
        // todo:: add 3rd sms sdk.
    }

    /**
     * 生成短信验证码
     * @param int $length
     * @return string
     * @author marhone
     */
    private function smsCodeGenerator($length = 6)
    {
        return str_pad(mt_rand(99, 999999), $length, '0', STR_PAD_LEFT);
    }
}