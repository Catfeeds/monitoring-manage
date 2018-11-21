<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpendSumAndReadNumToHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homeworks', function (Blueprint $table) {
            $table->unsignedInteger('spend_sum')->nullable()->comment('发布学生总人数');
            $table->unsignedInteger('read_sum')->default(0)->comment('阅读人数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homeworks', function (Blueprint $table) {
            $table->dropColumn('spend_sum');
            $table->dropColumn('read_num');
        });
    }
}
