<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_parent', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id')->index()->comment('学生id');
            $table->unsignedInteger('parent_id')->index()->comment('家长id');
            $table->string('role',20)->comment('家长角色');
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
        Schema::dropIfExists('student_parent');
    }
}
