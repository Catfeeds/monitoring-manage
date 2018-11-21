<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpenAtToCollectiveParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collective_parent', function (Blueprint $table) {
            $table->timestamp('expire_at')->default(now())->comment('到期时间');
            $table->dropColumn('status');
            $table->dropColumn('date_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collective_parent', function (Blueprint $table) {
            $table->dropColumn('expire_at');
        });
    }
}
