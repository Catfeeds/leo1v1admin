<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class TCreateUserPowerGroup extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('db_weiyi_admin.t_user_power_group');
        Schema::create('db_weiyi_admin.t_user_power_group', function (Blueprint $table){
            $table->increments('id');
                t_field($table->integer('uid'),"用户id");
                t_field($table->integer('gid'),"权限组id");
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