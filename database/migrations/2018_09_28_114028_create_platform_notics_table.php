<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformNoticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_notics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('admin_id')->comment('发布人id');
            $table->string('title',50)->comment('通知标题');
            $table->text('content')->comment('通知内容');
            $table->timestamps();
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
        Schema::dropIfExists('platform_notics');
    }
}
