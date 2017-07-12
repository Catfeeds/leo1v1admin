<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTUserAuthorityGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop('t_user_authority_group');
        Schema::create('t_user_authority_group', function (Blueprint $table){
            t_field($table->integer("groupid",true),"权限组id");
            t_field($table->string("group_name",100),"组名");
            t_field($table->string("group_authority_group",10000),"组权限");
            t_field($table->integer("create_time"),"创建时间");
            t_field($table->tinyInteger("del_flag"),"删除标志 0 未删除 1 已删除");
            t_field($table->tinyInteger("role"),"权限组所属角色 1学生 2老师 3助教 4家长");
            $table->unique("group_name");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
