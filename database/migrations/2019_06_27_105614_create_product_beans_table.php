<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBeansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_beans', function (Blueprint $table) {
            $table->comment = '书豆购买表';
            $table->increments('id');
            $table->char('name', 10)->unique()->comment('名称');
            $table->integer('amount')->default(0)->comment('书豆数量');
            $table->decimal('price', 8, 2)->default(0)->comment('价格');
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
        Schema::dropIfExists('product_beans');
    }
}
