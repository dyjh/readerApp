<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->comment = '订单子项表';
            $table->increments('id');
            $table->integer('student_id')->default(0)->index()->comment('用户id');
            $table->integer('order_id')->default(0)->index()->comment('订单id');
            $table->integer('product_id')->default(0)->index()->comment('商品id');
            $table->string('product_name')->comment('商品名称');
            $table->string('product_cover')->comment('商品封面');
            $table->string('product_desc')->comment('商品描述');
            $table->string('product_price')->comment('商品单价');
            $table->string('product_count')->comment('商品数量');
            $table->tinyInteger('statement')->default(1)
                ->comment('状态（1：待支付；2：待发货（已支付）；3：待收货（已发货）；4：待评价（已收货）；5：退款申请；6：已退款；7：交易取消）');
            $table->string('refund_no')->nullable()->comment('退款流水号');
            $table->decimal('refund_amount', 8, 2)->default(0)->comment('退款金额');
            $table->timestamp('refund_request_at')->nullable()->comment('退款申请时间');
            $table->tinyInteger('refund_success_at')->nullable('退款时间');
            $table->tinyInteger('refund_method')->default(0)->comment('服务方式（1：仅退款；2：退货退款）');
            $table->string('refund_reason')->comment('退款原因');
            $table->string('refund_remark')->comment('退款说明');
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
        Schema::dropIfExists('order_items');
    }
}
