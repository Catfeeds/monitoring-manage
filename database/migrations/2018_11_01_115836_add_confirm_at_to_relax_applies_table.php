<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfirmAtToRelaxAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relax_applies', function (Blueprint $table) {
            $table->timestamp('confirm_at')->nullable()->comment('确定时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relaxApplies', function (Blueprint $table) {
            $table->dropColumn('confirm_at');
        });
    }
}
