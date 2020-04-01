<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrow_comments', function (Blueprint $table) {
            $table->comment = '用户图书评论表';
            $table->increments('id');
            $table->integer('shared_book_id')->default(0)->index()->comment('图书id');
            $table->char('shared_book_name', 50)->comment('图书名称');
            $table->integer('student_id')->default(0)->index()->comment('用户id');
            $table->char('student_name', 20)->comment('用户名称');
            $table->string('student_avatar')->comment('用户头像');
            $table->string('content')->comment('评论内容');
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
        Schema::dropIfExists('borrow_comments');
    }
}
