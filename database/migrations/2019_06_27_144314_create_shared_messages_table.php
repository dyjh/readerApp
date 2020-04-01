<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_messages', function (Blueprint $table) {
            $table->comment = '用户对话消息记录表';
            $table->increments('id');
            $table->integer('from_student_id')->default(0)->index()->comment('发送者');
            $table->integer('to_student_id')->default(0)->index()->comment('接受者');
            $table->string('content')->comment('内容');
            $table->tinyInteger('content_type')->default(0)->comment('消息类型（...）');
            $table->timestamp('sent_at')->nullable()->comment('消息发送时间');
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
        Schema::dropIfExists('shared_messages');
    }
}
