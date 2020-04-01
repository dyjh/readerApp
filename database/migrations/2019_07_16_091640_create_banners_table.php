<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->comment('轮播图');
            $table->increments('id');
            $table->tinyInteger('type')->comment('类型(1:图文, 2:链接, 3:推荐课程)');
            $table->string('cover')->comment('轮播图');
            $table->string('href')->nullable()->comment('外链');
            $table->string('content')->nullable()->comment('图文内容');
            $table->integer('recommend_lesson_id')->nullable()->comment('推荐课程');
            $table->integer('sort')->nullable()->comment('显示顺序');
            $table->boolean('status')->default(1)->comment('启用状态（1：启用；0：禁用）');
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
        Schema::dropIfExists('banners');
    }
}
