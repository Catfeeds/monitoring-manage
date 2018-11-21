<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectiveParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collective_parent', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('class_id')->index()->comment('班级id');
            $table->unsignedInteger('parent_id')->index()->comment('家长id');
            $table->unsignedTinyInteger('status')->default(0)->comment('视频在线状态0未开通1已开通');
            $table->unsignedInteger('date_num')->default(0)->comment('到期时间天数');
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
        Schema::dropIfExists('collective_parent');
    }
}
