<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPostagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_postages', function (Blueprint $table) {
            $table->comment = '订单物流信息表';
            $table->increments('order_id');
            $table->string('name')->default('')->comment('快递商名称');
            $table->unsignedMediumInteger('price')->default(0)->comment('运费价格');
            $table->string('express_number')->default('')->comment('快递单号');
            $table->string('province',50)->comment('省');
            $table->string('city',50)->comment('市');
            $table->string('district',50)->comment('区域');
            $table->string('address')->comment('详细地址');
            $table->string('contact_name',20)->comment('联系人');
            $table->string('contact_number',20)->comment('联系电话');
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
        Schema::dropIfExists('order_postages');
    }
}
