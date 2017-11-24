<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TUserInfoPasswdMd5Two extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //db_account.t_user_info
        Schema::table('db_account.t_user_info', function (Blueprint $table) {
            t_field($table->string('passwd_md5_two',32),"密码2次md5保存: md5(md5(passwd).\"@leo1v1\")" );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
