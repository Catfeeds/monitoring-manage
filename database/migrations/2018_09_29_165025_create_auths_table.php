<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auths', function (Blueprint $table) {
            $table->increments('id');
//            $table->unsignedInteger('user_id')->comment('家长用户id');
//            $table->string('school_id')->comment('学校id');
            $table->string('class_id')->comment('班级id');
            $table->string('student_id')->index()->comment('学生id');
            $table->string('info')->default('')->comment('其他的填写信息');
            $table->string('remark')->default('')->comment('备注');
            $table->unsignedInteger('operator')->nullable()->comment('审核人id');
            $table->unsignedTinyInteger('status')->default(1)->comment('审核状态');
            $table->string('refusal_reason')->default('')->comment('拒绝理由');
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
        Schema::dropIfExists('auths');
    }
}
