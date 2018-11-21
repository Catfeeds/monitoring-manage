<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectiveNoticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collective_notic', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('class_id')->index()->comment('班级id');
            $table->unsignedInteger('notic_id')->index()->comment('消息id');
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
        Schema::dropIfExists('collective_notic');
    }
}
