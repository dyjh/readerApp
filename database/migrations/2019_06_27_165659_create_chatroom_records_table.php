<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatroomRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatroom_records', function (Blueprint $table) {
            $table->comment = '课程直播聊天记录表';
            $table->increments('id');
            $table->integer('lesson_id')->default(0)->index()->comment('课程id');
            $table->integer('student_id')->default(0)->index()->comment('用户id');
            $table->integer('content')->default(0)->index()->comment('内容');
            $table->boolean('is_content_valid')->default(1)->comment('内容是否合法（1：是；0：否）');
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
        Schema::dropIfExists('chatroom_records');
    }
}
