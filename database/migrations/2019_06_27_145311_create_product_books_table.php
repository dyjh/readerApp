<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_books', function (Blueprint $table) {
            $table->comment = '特价图书表';
            $table->increments('id');
            $table->char('name', 120)->index()->comment('书名');
            $table->char('author', 50)->index()->comment('作者');
            $table->char('publisher', 50)->comment('出版社');
            $table->char('isbn', 20)->comment('ISBN 编码');
            $table->string('cover')->comment('封面');
            $table->json('videos')->nullable()->commnet('视频简介');
            $table->json('info_images')->nullable()->comment('图片');
            $table->string('info_text')->comment('文字描述');
            $table->integer('stock')->default(0)->comment('库存量');
            $table->decimal('tag_price', 8, 2)->default(0)->comment('标价');
            $table->decimal('sell_price', 8, 2)->default(0)->comment('售价');
            $table->float('discount', 8, 2)->default(0)->comment('折扣');
            $table->integer('sales')->default(0)->comment('销量');
            $table->unsignedInteger('on_sale')->default(1)->comment('商品上架状态 1：上架 0：下架');
            $table->float('rates', 4, 1)->comment('综合评分');
            $table->integer('comment_counts')->default(0)->comment('评论条数');
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
        Schema::dropIfExists('product_books');
    }
}
