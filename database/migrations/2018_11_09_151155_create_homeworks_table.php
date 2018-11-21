<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeworks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('admin_id')->comment('发布人id');
            $table->string('title',50)->comment('作业标题');
            $table->text('content')->comment('作业内容');
            $table->timestamp('end_at')->comment('截止时间');
            $table->unsignedInteger('class_id')->index()->nullable()->comment('班级id');
            $table->timestamps();
            $table->index('school_id');
            $table->index('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homeworks');
    }
}
