<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->comment = '课程表';
            $table->increments('id');
            $table->integer('teacher_id')->default(0)->index()->comment('讲师id');
            $table->integer('lesson_category_id')->default(0)->index()->comment('课程学科分类id');
            $table->integer('grade_id')->default(0)->index()->comment('年级id');
            $table->integer('semester_id')->default(0)->index()->comment('学期id');
            $table->tinyInteger('tag')->nullable()->comment('课程标签');
            $table->char('name', 120)->comment('名称');
            $table->string('desc')->comment('课程描述');
            $table->decimal('price', 8, 2)->default(0)->comment('课程价格');
            $table->decimal('list_price', 8, 2)->default(0)->comment('课程标价');
            $table->date('sign_dead_line')->nullable()->comment('报名截止时间');
            $table->integer('sign_count')->default(0)->comment('报名人数');
            $table->integer('view_count')->default(0)->comment('浏览人数');
            $table->integer('lesson_hour_count')->default(0)->comment('课时数');
            $table->boolean('is_streamed')->default(1)->commennt('是否是直播课程（1：是；0：否）');
            $table->date('broadcast_day_begin')->comment('直播开始日期');
            $table->date('broadcast_day_end')->comment('直播结束日期');
            $table->time('broadcast_start_at')->comment('直播开始时间');
            $table->time('broadcast_ent_at')->comment('直播结束时间');
            $table->string('cover')->comment('课程封面');
            $table->string('prevideo')->comment('预览视频');
            $table->string('preimage')->comment('预览视频图片');
            $table->json('images')->comment('课程预览图片');
            $table->string('preview_url')->nullable()->comment('视屏预览地址');
            $table->integer('rates')->default(0)->comment('用户综合评分');
            $table->boolean('recommended')->default(1)->comment('推荐状态（1：是；0：否）');
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
        Schema::dropIfExists('lessons');
    }
}
