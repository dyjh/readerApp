<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_comments', function (Blueprint $table) {
            $table->comment = '课程评论表';
            $table->increments('id');
            $table->integer('lesson_id')->default(0)->index()->comment('课程id');
            $table->integer('student_id')->default(0)->index()->comment('用户id');
            $table->string('student_avatar')->comment('用户头像');
            $table->float('rate', 4, 1)->default(0)->comment('课程评分');
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
        Schema::dropIfExists('lesson_comments');
    }
}
