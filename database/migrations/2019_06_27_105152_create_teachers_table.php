<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->comment = '讲师表';
            $table->increments('id');
            $table->char('name', 20)->unique()->comment('名称');
            $table->string('avatar')->comment('头像');
            $table->char('title', 20)->comment('头衔');
            $table->string('profile')->comment('介绍');
            $table->tinyInteger('status')->default(1)->comment('启用状态（1：启用；0：禁用）');
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
        Schema::dropIfExists('teachers');
    }
}
