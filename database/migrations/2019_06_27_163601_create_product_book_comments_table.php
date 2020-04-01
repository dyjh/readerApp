<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBookCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_book_comments', function (Blueprint $table) {
            $table->comment = '特价图书评论表';
            $table->increments('id');
            $table->integer('order_id')->default(0)->index()->comment('订单id');
            $table->integer('product_book_id')->default(0)->index()->comment('图书id');
            $table->integer('student_id')->default(0)->index()->comment('用户id');
            $table->float('desc_match_rate', 4, 1)->default(5)->comment('描述评分');
            $table->float('service_attitude_rate', 4, 1)->default(5)->comment('服务态度评分');
            $table->string('content')->comment('评论内容');
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
        Schema::dropIfExists('product_book_comments');
    }
}
