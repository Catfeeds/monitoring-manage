<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankInfoToPayConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pay_configs', function (Blueprint $table) {
            $table->string('bank_name')->default('暂无')->comment('银行名称');
            $table->string('bank_card')->default('暂无')->comment('银行卡号');
            $table->string('bank_man')->default('暂无')->comment('开户人');
            $table->string('bank_place')->default('暂无')->comment('开户地址');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pay_configs', function (Blueprint $table) {
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_card');
            $table->dropColumn('bank_man');
            $table->dropColumn('bank_place');
        });
    }
}
