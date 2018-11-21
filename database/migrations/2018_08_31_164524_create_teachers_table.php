<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',30);
            $table->string('tel',20);
            $table->string('avatar',255);
            $table->unsignedInteger('class_id')->nullable();
            $table->string('note',255)->nullable();
            $table->unsignedInteger('admin_id');
            $table->unsignedInteger('state')->default(1);
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('sex');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
