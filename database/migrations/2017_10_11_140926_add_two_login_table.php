<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwoLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi_admin.t_ssh_login_log', function (Blueprint $table) {
            t_field($table->integer("login_succ_flag"),"是否登录成功");
            t_field($table->integer("login_ip"),"登录IP");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_ssh_login_log', function (Blueprint $table) {
            //
        });
    }
}
