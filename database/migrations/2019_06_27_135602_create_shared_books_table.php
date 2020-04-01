<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_books', function (Blueprint $table) {
            $table->comment = '共享图书表';
            $table->increments('id');
            $table->integer('school_id')->default(0)->comment('学校id');
            $table->integer('grade_id')->default(0)->comment('年级id');
            $table->integer('ban_id')->default(0)->comment('班级id');
            $table->char('name', 50)->index()->comment('书名');
            $table->char('author', 50)->index()->comment('作者');
            $table->char('publisher', 50)->comment('出版社');
            $table->char('isbn', 20)->unique()->comment('ISBN 编码');
            $table->string('cover')->comment('封面');
            $table->string('desc')->comment('简介');
            $table->integer('rent_counts')->default(0)->comment('借阅总次数');
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
        Schema::dropIfExists('shared_books');
    }
}
