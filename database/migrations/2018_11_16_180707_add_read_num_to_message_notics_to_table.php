<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadNumToMessageNoticsToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_notics', function (Blueprint $table) {
            $table->unsignedInteger('read_num')->default(0)->comment('已读人数');
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
            $table->dropColumn('read_num');
        });
    }
}
