<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TKaoqin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //
        Schema::create('db_weiyi_admin.t_kaoqin_machine', function (Blueprint $table){
            t_field($table->integer("machine_id",true),"机器id");
            t_field($table->integer("open_door_flag"),"是否开启门禁");
            t_field($table->integer("last_post_time"),"最后一次上报时间");
            t_field($table->string("sn"),"考勤机序列号");
            t_field($table->string("title"),"标题");
            t_field($table->string("desc"),"说明");
            $table->unique(["sn"]);
        });

        Schema::create('db_weiyi_admin.t_kaoqin_machine_adminid', function (Blueprint $table){
            t_field($table->integer("machine_id"),"机器id");
            t_field($table->integer("adminid"),"");
            t_field($table->integer("auth_flag"),"管理员标示");
            $table->primary(["machine_id", "adminid" ], "pri_key" );
            $table->index([ "adminid" ], "adminid" );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('db_weiyi_admin.t_kaoqin_machine_adminid');
        Schema::drop('db_weiyi_admin.t_kaoqin_machine');
        //
    }
}
