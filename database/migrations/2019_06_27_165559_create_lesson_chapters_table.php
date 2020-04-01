<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_chapters', function (Blueprint $table) {
            $table->comment = '课程小节表';
            $table->increments('id');
            $table->integer('lesson_id')->default(0)->index()->comment('课程id');
            $table->char('catalog_id', 120)->unique()->comment('目录id');
            $table->char('name', 120)->comment('小节名称');
            $table->date('broadcast_day')->nullable()->comment('直播日期');
            $table->time('broadcast_start_at')->nullable()->comment('直播开始时间');
            $table->time('broadcast_ent_at')->nullable()->comment('直播结束时间');
            $table->string('play_back_url')->nullable()->comment('视频回放地址');
            $table->boolean('is_streamed')->default(false)->comment('是否是直播课程（1：是；0：否）');
            $table->string('stream_key')->nullable()->comment('直播流名');
            $table->string('rtmp_publish_url')->nullable()->comment('RTMP推流地址');
            $table->string('stream_url')->nullable()->comment('直播流播放地址');
            $table->json('stream_play_urls');
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
        Schema::dropIfExists('lesson_chapters');
    }
}
