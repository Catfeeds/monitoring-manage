<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('school_id')->index()->comment('学校id');
            $table->string('alipay_code')->nullable()->comment('支付宝二维码');
            $table->string('wechat_code')->nullable()->comment('微信二维码');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_configs');
    }
}
