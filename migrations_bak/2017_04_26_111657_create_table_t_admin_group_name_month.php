<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAdminGroupNameMonth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_group_name_month', function (Blueprint $table){
            t_field($table->integer("groupid"),"groupid");
            t_field($table->integer("month"),"月度时间,以每月一日");
            t_field($table->integer("main_type"),"部门类型");
            t_field($table->string("group_name"),"组名");
            t_field($table->integer("master_adminid"),"助长id");
            t_field($table->integer("up_groupid"),"上一级groupid");
            t_field($table->string("group_assign_percent"),"分配比例");
            $table->primary(["groupid","month"]);
            $table->index("main_type","main_type");
        });

        Schema::create('db_weiyi_admin.t_group_user_month', function (Blueprint $table){
            t_field($table->integer("groupid"),"groupid");
            t_field($table->integer("adminid"),"后台adminid");
            t_field($table->integer("month"),"月度时间,以每月一日");
            t_field($table->string("assign_percent"),"分配比例");
            $table->primary(["groupid","adminid","month"]);
        });

        Schema::create('db_weiyi_admin.t_main_group_name_month', function (Blueprint $table){
            t_field($table->integer("groupid"),"groupid");
            t_field($table->integer("month"),"月度时间,以每月一日");
            t_field($table->integer("main_type"),"部门类型");
            t_field($table->string("group_name"),"组名");
            t_field($table->integer("master_adminid"),"助长id");
            t_field($table->string("group_assign_percent"),"分配比例");
            $table->primary(["groupid","month"]);
            $table->index("main_type","main_type");
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
