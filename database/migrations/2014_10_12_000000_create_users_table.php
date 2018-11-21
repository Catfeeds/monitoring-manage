<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',20)->comment('家长名称');
            $table->string('phone',20)->comment('联系方式');
            $table->string('password');
            $table->unsignedTinyInteger('way')->default(0)->comment('家长来源0后天录入1老师邀请');
            $table->rememberToken();
            $table->timestamps();
            $table->index('way');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
