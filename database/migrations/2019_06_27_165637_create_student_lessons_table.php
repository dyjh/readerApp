<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_lessons', function (Blueprint $table) {
            $table->comment = '用户课程表';
            $table->increments('id');
            $table->integer('student_id')->default(0)->comment('用户id');
            $table->integer('lesson_id')->default(0)->comment('课程id');
            $table->tinyInteger('payment_statement')->default(1)->comment('支付状态（0：未支付；1：已支付；3：申请退款；4：已退款）');
            $table->decimal('total', 8, 2)->default(0)->comment('已支付的金额');
            $table->tinyInteger('payment_method')->default(0)->comment('支付方式（1：支付宝；2：微信）');
            $table->string('trade_no')->unique()->comment('订单号');
            $table->string('trans_no')->nullable()->comment('交易流水号');
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
        Schema::dropIfExists('student_lessons');
    }
}
