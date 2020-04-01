<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags_lessons', function (Blueprint $table) {
            $table->comment = '课程标签中间表';
            $table->increments('id');
            $table->integer('lesson_id')->default(0)->comment('课程id');
            $table->integer('lesson_tag_id')->default(0)->comment('标签id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags_lessons');
    }
}
