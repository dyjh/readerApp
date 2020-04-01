<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->comment = '购物车表';
            $table->increments('id');
            $table->integer('student_id')->default(0)->index()->comment('用户id');
            $table->integer('product_book_id')->default(0)->index()->comment('图书id');
            $table->char('product_book_name', 120)->comment('书名');
            $table->string('product_book_desc')->comment('图书描述');
            $table->string('product_book_cover')->comment('图书封面');
            $table->decimal('product_book_price', 8, 2)->comment('图书价格');
            $table->integer('product_count')->default(0)->comment('数量');
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
        Schema::dropIfExists('carts');
    }
}
