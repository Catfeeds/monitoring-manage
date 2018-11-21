<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMastersOfAllRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('collectives', function (Blueprint $table) {
//            $table->unsignedInteger('master_id')->comment('班主任ID');
//        });
//
//        Schema::table('grades', function (Blueprint $table) {
//            $table->unsignedInteger('master_id')->comment('年段长ID');
//        });
//
//        Schema::table('schools', function (Blueprint $table) {
//            $table->unsignedInteger('master_id')->comment('校长ID');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('collectives', function (Blueprint $table) {
//            $table->dropColumn('master_id');
//        });
//
//        Schema::table('grades', function (Blueprint $table) {
//            $table->dropColumn('master_id');
//        });
//
//        Schema::table('schools', function (Blueprint $table) {
//            $table->dropColumn('master_id');
//        });
    }
}
