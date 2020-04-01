<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->comment = '用户收货地址表';
            $table->increments('id');
            $table->integer('student_id')->default(0)->comment('学生id');
            $table->string('city')->comment('市');
            $table->string('province')->comment('省');
            $table->string('district')->comment('县');
            $table->string('address')->comment('详细地址');
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
        Schema::dropIfExists('ships');
    }
}
