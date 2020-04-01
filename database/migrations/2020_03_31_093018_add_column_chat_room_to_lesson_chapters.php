<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnChatRoomToLessonChapters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_chapters', function (Blueprint $table) {
            //
            $table->string('chat_room', 100)->default('{}')->comment('聊天室信息');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_chapters', function (Blueprint $table) {
            //
            $table->dropColumn(['chat_room']);
        });
    }
}
