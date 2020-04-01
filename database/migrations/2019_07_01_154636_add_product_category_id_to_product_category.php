<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductCategoryIdToProductCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_books', function (Blueprint $table) {
            $table->unsignedInteger('product_category_id')->index()->comment('类型id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_books', function (Blueprint $table) {
            $table->dropColumn('product_category_id');
        });
    }
}
