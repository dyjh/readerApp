<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->comment = '支付、收据记录表';
            $table->increments('id');
            $table->unsignedInteger('student_id')->index();
            $table->string('payable_id')->comment('付款对象');
            $table->string('payable_type', 50)->comment('付款对象类型');
            $table->enum('platform', [
                'alipay',
                'wechatpay',
            ])->comment('支付平台');
            $table->enum('type', [
                'JSAPI',
                'NATIVE',
                'APP'
            ])->comment('支付类型');
            $table->string('title')->default('')->comment('标题');
            $table->decimal('total')->comment('付款金额');
            $table->text('original')->comment('预付款订单数据');
            $table->string('trade_no')->nullable()->comment('三方订单号');
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
            $table->boolean('paid')->default(false)->comment('是否支付成功');
            $table->timestamp('expired_at')->nullable()->comment('过期时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
