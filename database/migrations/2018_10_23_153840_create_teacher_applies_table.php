<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_applies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',30);
            $table->string('tel',20);
            $table->string('password');
            $table->string('avatar',255);
            $table->unsignedInteger('sex');
            $table->unsignedInteger('school_id')->index()->comment('学校id');
            $table->unsignedInteger('class_id')->index()->comment('班级id');
            $table->unsignedInteger('status')->default(0)->index()->comment('状态0未审核1已同意2已拒绝');
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
        Schema::dropIfExists('teacher_applies');
    }
}
