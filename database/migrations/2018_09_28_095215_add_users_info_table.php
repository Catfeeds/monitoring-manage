<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {

            $table->string('nickname','20')->nullable()->comment("用户昵称");
            $table->unsignedTinyInteger('sex')->default(3)->comment("性别:1男2女3保密");
            $table->unsignedInteger('birthday')->nullable()->comment("生日");
            $table->unsignedTinyInteger('status')->default(1)->comment("用户状态");
            $table->unsignedTinyInteger('grades')->default(1)->comment("用户等级");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nickname');
            $table->dropColumn('sex');
            $table->dropColumn('birthday');
            $table->dropColumn('status');
            $table->dropColumn('grades');
        });
    }
}
