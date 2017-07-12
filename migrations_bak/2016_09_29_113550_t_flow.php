<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFlow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_flow', function (Blueprint $table)
        {
            $table->integer("flowid",true); //审批id
            $table->integer("flow_type"); //审批类型
            $table->integer("post_adminid"); //提交人
            $table->integer("post_time"); //提交时间

            $table->integer("from_key_int")->nullable(); //
            $table->string("from_key_str",64)->nullable(); //

            $table->string("post_msg",4096); //提交信息
            $table->integer("flow_status"); //状态
            $table->integer("flow_status_time"); //状态时间
            
            $table->unique( ["flow_type" , "from_key_str"] );
            $table->unique( ["flow_type" , "from_key_int"] );
        });

        Schema::create('db_weiyi_admin.t_flow_node', function (Blueprint $table)
        {
            $table->integer("nodeid",true); //进度id
            $table->integer("node_type"); //
            $table->integer("flowid"); //
            $table->integer("adminid"); //
            $table->integer("add_time"); //
            $table->integer("flow_check_flag"); //
            $table->integer("check_time"); //
            $table->string("check_msg",1028); //提交信息
            $table->integer("next_nodeid"); //下个节点
            
            $table->index("flowid");
            $table->index(["adminid", "flow_check_flag"]);
        });

        //请假

        Schema::create('db_weiyi_admin.t_jiaqi_year_count', function (Blueprint $table)
        {
            $table->integer("adminid"); //
            $table->integer("year"); //
            $table->integer("year_hour_count"); //年假
            $table->integer("sick_hour_count"); //病假
            $table->integer("absence_hour_count"); //事假
            $table->primary(["adminid","year"]);
            $table->index("year" );
        });

        Schema::create('db_weiyi_admin.t_qingjia', function (Blueprint $table)
        {
            $table->integer("id", true); 
            $table->integer("adminid"); 
            $table->integer("add_time"); 
            $table->integer("type"); 
            $table->integer("start_time"); 
            $table->integer("end_time"); 
            $table->integer("hour_count"); 
            $table->integer("del_flag"); 
            $table->string("msg"); 
            $table->index("adminid");
            $table->index("add_time");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("db_weiyi_admin.t_flow");
        Schema::drop("db_weiyi_admin.t_flow_node");
        Schema::drop("db_weiyi_admin.t_jiaqi_year_count");
        Schema::drop("db_weiyi_admin.t_qingjia");
        //
    }
}
