<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('version_no')->default('1.0')->comment('版本号');
            $table->string('download_url')->comment('下载地址');
            $table->string('status')->comment('APP类型android或apple');
            $table->timestamps();
        });

        $version = ['download_url' => '','status' => 'android'];

        DB::table('versions')->insert($version);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('versions');
    }
}
