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
            $table->tinyInteger('type')->comment('用户类型');
            $table->string('nickname', 100)->comment('昵称');
            $table->string('avatar')->comment('头像');
            $table->string('openid')->comment('开放id');
            $table->string('access_token')->comment('登录校验');
            $table->string('last_login_ip')->comment('上次登录ip');
            $table->integer('login_counts')->comment('登录次数');
            $table->tinyInteger('is_black')->comment('是否拉黑');
            $table->string('remark')->comment('备注');
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
        Schema::dropIfExists('users');
    }
}
