<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTeachersTableSomecow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('teachers', function (Blueprint $table) {
           $table->timestamp('birthday')->nullable();
           $table->string('graduate',50)->nullable();
           $table->string('education',8)->nullable();
           $table->unsignedInteger('teach_age')->nullable();
           $table->string('position',20)->nullable();
           $table->string('elegant',200)->nullable();
           $table->string('honor',200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
