<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStatusToLessonChapters extends Migration
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
            $table->unsignedSmallInteger('live_status')->default(0)->commet('直播状态 0 未在直播 1 正在直播');
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
            $table->dropColumn(['live_status']);
        });
    }
}
