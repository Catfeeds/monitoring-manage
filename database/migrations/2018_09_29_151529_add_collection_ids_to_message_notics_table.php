<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollectionIdsToMessageNoticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_notics', function (Blueprint $table) {
            $table->string('collection_ids')->nullable()->comment('班级id(数组)');
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
            $table->dropColumn('collection_ids');
        });
    }
}
