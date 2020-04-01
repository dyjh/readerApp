<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->comment = '用户表';
            $table->increments('id');
            $table->char('name', 20)->unique()->comment('用户名');
            $table->char('realname', 20)->unique()->comment('真实姓名');
            $table->string('avatar')->nullable()->comment('头像');
            $table->integer('total_beans')->default(0)->comment('书豆');
            $table->char('password')->comment('密码');
            $table->char('phone', 18)->unique()->comment('手机号');
            $table->string('city')->index()->comment('城市');
            $table->string('province')->index()->comment('省');
            $table->string('district')->index()->comment('县');
            $table->integer('school_id')->default(0)->comment('学校id');
            $table->string('school_name')->comment('学校名称');
            $table->integer('grade_id')->default(0)->comment('年级id');
            $table->string('grade_name')->comment('年级名称');
            $table->integer('ban_id')->default(0)->comment('班级id');
            $table->string('ban_name')->comment('班级名称');
            $table->integer('read_count')->default(0)->comment('图书阅读数');
            $table->integer('share_count')->default(0)->comment('图书上传数');
            $table->tinyInteger('in_blacklist')->default(0)->comment('是否在借书黑名单(1:是; 0:否)');
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
        Schema::dropIfExists('students');
    }
}
