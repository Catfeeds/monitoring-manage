<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageNoticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_notics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('admin_id')->comment('发布人id');
            $table->string('title',50)->comment('通知标题');
            $table->text('content')->comment('通知内容');
            $table->unsignedTinyInteger('scope')->comment('发布范围1全体2指定年段3指定班级');
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
        Schema::dropIfExists('message_notics');
    }
}
