<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shared_book_id')->default(0)->comment('图书id');
            $table->integer('student_id')->default(0)->comment('用户id');
            $table->integer('rent_count')->default(0)->comment('借阅次数');
            $table->boolean('is_in_shelf')->default(1)->comment('是否上架');
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
        Schema::dropIfExists('private_books');
    }
}
