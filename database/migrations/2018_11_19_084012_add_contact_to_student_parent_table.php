<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactToStudentParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_parent', function (Blueprint $table) {
            $table->unsignedTinyInteger('contact')->default(0)->comment('第几联系人');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_parent', function (Blueprint $table) {
            $table->dropColumn('contact');
        });
    }
}
