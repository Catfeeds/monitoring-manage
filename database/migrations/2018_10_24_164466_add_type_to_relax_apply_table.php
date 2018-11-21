<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToRelaxApplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relax_applies', function (Blueprint $table) {
            $table->unsignedTinyInteger('type')->default(1)->comment('请假类型 1事假 2病假');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relax_applies', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
