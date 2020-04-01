<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_signs', function (Blueprint $table) {
            $table->comment = '用户签到表';
            $table->increments('id');
            $table->integer('student_id')->default(0);
            $table->char('month', 10)->comment('本月');
            $table->integer('mask')->default(0)->comment('签到数据');
            $table->integer('continue_days')->default(0)->comment('用户本月连续签到的天数');
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
        Schema::dropIfExists('student_signs');
    }
}
