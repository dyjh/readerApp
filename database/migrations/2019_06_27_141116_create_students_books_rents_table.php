<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsBooksRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_books_rents', function (Blueprint $table) {
            $table->comment = '用户图书借阅记录表';
            $table->increments('id');
            $table->integer('private_book_id')->default(0)->index()->comment('用户图书id');
            $table->integer('renter_id')->default(0)->index()->comment('借阅人id');
            $table->char('renter_name', 20)->index()->comment('借阅人名称');
            $table->integer('lender_id')->default(0)->index()->comment('借出人id');
            $table->char('lender_name')->index()->comment('借出人名称');
            $table->integer('shared_book_id')->default(0)->comment('图书id');
            $table->char('shared_book_name', 50)->comment('图书名称');
            $table->string('shared_book_cover')->comment('图书封面');
            $table->tinyInteger('statement')->default(1)->comment('状态（1：申请中；2：不同意借阅；4：借阅中；5：归还中；6：完成借阅）');
            $table->timestamp('rend_applied_at')->nullable()->comment('发起借书申请时间戳');
            $table->timestamp('rend_cancel_at')->nullable()->comment('取消借阅申请时间戳');
            $table->timestamp('rend_allowed_at')->nullable()->comment('同意借阅时间戳');
            $table->timestamp('rend_rejected_at')->nullable()->comment('拒绝借阅时间戳');
            $table->timestamp('return_applied_at')->nullable()->comment('发起归还申请时间戳');
            $table->timestamp('return_confirm_at')->nullable()->comment('确定图书归还时间戳');
            $table->integer('cast_beans')->default(0)->comment('借书消耗的书豆');
            $table->integer('over_limit_days')->default(0)->comment('超过借书归还天数');
            $table->tinyInteger('blacked')->default(0)->comment('是否超期未还(1:是; 0:否)');
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
        Schema::dropIfExists('students_books_rents');
    }
}
