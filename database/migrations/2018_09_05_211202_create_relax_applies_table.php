<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelaxAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relax_applies', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('school_id')->index()->comment('学校ID');
            $table->unsignedInteger('user_id')->index()->comment('请假家长');
            $table->unsignedInteger('student_id')->index()->comment('请假学生');
            $table->text('reason')->comment('请假原因')->nullable();
            $table->string('begin')->comment('开始时间');
            $table->string('end')->comment('结束时间');
            $table->float('date_num')->comment('请假时长');
            $table->unsignedInteger('teacher_id')->index()->comment('审核老师id');
            $table->unsignedTinyInteger('status')->default(1)->index()->comment('状态');
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
        Schema::dropIfExists('relax_applies');
    }
}
