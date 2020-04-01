<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_invitations', function (Blueprint $table) {
            $table->comment = '用户邀请表';
            $table->increments('id');
            $table->integer('student_id')->comment('用户id');
            $table->string('invitation_code')->unique()->comment('邀请码');
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
        Schema::dropIfExists('student_invitations');
    }
}
