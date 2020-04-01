<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentLessonHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_lesson_histories', function (Blueprint $table) {
            $table->comment = '用户课程浏览历史表';
            $table->increments('id');
            $table->integer('student_id')->default(0)->index()->comment('用户id');
            $table->integer('lesson_id')->default(0)->index()->comment('课程id');
            $table->integer('lesson_chapter_id')->default(0)->index()->comment('课程id');
            $table->string('name')->comment('记录名称');
            $table->timestamp('watched_at')->comment('观看时间');
            $table->string('watched_minutes')->comment('观看时长');
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
        Schema::dropIfExists('student_lesson_histories');
    }
}
