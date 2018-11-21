<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSumNumToMessageNoticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_notics', function (Blueprint $table) {
            $table->unsignedInteger('sum_num')->default(0)->comment('发送人数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_notics', function (Blueprint $table) {
            $table->dropColumn('sum_num');
        });
    }
}
