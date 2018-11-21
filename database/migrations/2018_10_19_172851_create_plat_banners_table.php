<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plat_banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cover')->comment('封面图');
            $table->string('link')->comment('链接');
            $table->unsignedTinyInteger('link_type')->comment('链接类型0外部链接1内部文章');
            $table->unsignedInteger('order')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(1)->comment('是否启用0否1是');
            $table->timestamps();
            $table->index('link_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plat_banners');
    }
}
