<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->comment = '订单表';
            $table->increments('id');
            $table->integer('student_id')->default(0)->index()->comment('用户id');
            $table->integer('ship_address_id')->default(0)->index()->comment('收货地址id');
            $table->char('ship_code', 50)->nullable()->comment('快递物流编码');
            $table->string('trade_no')->unique()->comment('订单号');
            $table->string('trans_no')->nullable()->comment('交易流水号');
            $table->decimal('tag_price', 8, 2)->default(0)->comment('标价');
            $table->decimal('sell_price', 8, 2)->default(0)->comment('售价');
            $table->decimal('total', 8, 2)->default(0)->comment('实际付款');
            $table->decimal('refund_total', 8, 2)->default(0)->comment('退款总金额');
            $table->tinyInteger('statement')->default(1)
                ->comment('状态（1：待支付；2：待发货（已支付）；3：待收货（已发货）；4：待评价（已收货）；5：退款申请；6：已退款；7：交易取消[超过规定的支付时间没有支付，或用户取消]）');
            $table->decimal('total_amount', 8, 2)->default(0)->comment('商品数量');
            $table->tinyInteger('payment_method')->default(0)->comment('支付方式（1：支付宝；2：微信）');
            $table->timestamp('place_at')->nullable()->comment('下单时间');
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}
