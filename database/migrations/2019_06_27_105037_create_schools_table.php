<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->comment = "学校表";
            $table->increments('id');
            $table->string('name',20)->comment('学校名称');
            $table->string('city',20)->comment('城市');
            $table->string('province',20)->comment('省份');
            $table->string('district',20)->comment('区域');
            $table->string('telephone',20)->default('')->comment('联系电话');
            $table->string('school_type',20)->comment('学校类型');
            $table->string('approach',20)->default('')->comment('入学途径');
            $table->string('special',50)->default('')->comment('特殊招生');
            $table->string('address',100)->comment('详细地址');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态 1：启用 0：禁用');
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
        DB::statement("ALTER TABLE `admin_user` comment'平台:用户表'");
        Schema::dropIfExists('schools');
    }
}
