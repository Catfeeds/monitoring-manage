<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollectiveQrcodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collectives', function (Blueprint $table) {
            $table->string('qrcode')->default('')->comment('二维码');
            $table->string('sn')->default('')->index()->comment('标识码');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collectives', function (Blueprint $table) {
            $table->dropColumn('qrcode');
            $table->dropColumn('sn');
        });
    }
}
