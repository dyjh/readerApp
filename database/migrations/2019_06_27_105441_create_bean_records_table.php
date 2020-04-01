<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeanRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bean_records', function (Blueprint $table) {
            $table->comment = '书豆记录表';
            $table->increments('id');
            $table->integer('student_id')->defaut(0)->comment('用户id');
            $table->tinyInteger('changed_by')
                ->default(0)
                ->comment('增加方式（1：充值；2：分享；3：签到；4：借书消耗）');
            $table->integer('amount')->default(0)->comment('书豆变化量（正数：增加量；负数：减少量）');
            $table->timestamp('changed_at')->nullable()->comment('变化时间');
            $table->integer('before_beans_total')->default(0)->comment('改变前的书豆');
            $table->integer('after_beans_total')->default(0)->comment('改变后的书豆');
            $table->tinyInteger('payment_method')->nullable()->comment('支付方式（1：支付宝；2：微信）');
            $table->string('trade_no')->unique()->nullable()->comment('订单号');
            $table->string('trans_no')->unique()->nullable()->comment('交易流水号');
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
        Schema::dropIfExists('bean_records');
    }
}
