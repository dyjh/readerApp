<?php

namespace App\Providers;

use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Yansongda\Pay\Pay;
use Monolog\Logger;

class AppServiceProvider extends ServiceProvider
{

    protected $implementations = [
        'App\Models\stores\Contracts\OrderInterface'           => 'App\Models\stores\Order',
        'App\Services\store\Contracts\OrderGeneratorInterface' => 'App\Services\store\OrderGenerator',
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191); //add fixed sql

        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            // /^1[34578][0-9]{9}$/
            return preg_match('/^1[34578][0-9]{9}$/', $value) > 0 ? true : false;
        });

        $this->bindImplementation();
    }

    protected function bindImplementation()
    {
        foreach ($this->implementations() as $interface => $implementation)
            $this->app->bind($interface, $implementation);
    }

    protected function implementations()
    {
        return $this->implementations;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FakerGenerator::class, function () {
            return FakerFactory::create('zh_CN');
        });

        //支付宝
        $this->app->singleton('alipay', function () {
            $config = config('pay.alipay');
            $config['notify_url'] = route('order-ali-notify');
            $config['return_url'] = route('order-ali-return');
            // 判断当前项目运行环境是否为线上环境
            if (app()->environment() !== 'production') {
                $config['mode']         = 'dev';
                $config['log']['level'] = Logger::DEBUG;
            } else {
                $config['log']['level'] = Logger::WARNING;
            }
            // 调用 Yansongda\Pay 来创建一个支付宝支付对象
            return Pay::alipay($config);
        });

        //微信
        $this->app->singleton('wechat_pay', function () {
            $config = config('pay.wechat');
            $config['notify_url'] = route('order-wx-notify');
            if (app()->environment() !== 'production') {
                $config['log']['level'] = Logger::DEBUG;
            } else {
                $config['log']['level'] = Logger::WARNING;
            }
            // 调用 Yansongda\Pay 来创建一个微信支付对象
            return Pay::wechat($config);
        });
    }
}
