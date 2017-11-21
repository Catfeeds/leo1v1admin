<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFlowNodeAddEx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::table('db_weiyi_admin.t_flow_node_config', function( Blueprint $table)
        {
            t_field($table->integer("id",true),"");
            t_field($table->integer("flow_type"),"审批类型");
            t_field($table->integer("flow_node_type_id"),"审批节点类型id");
            t_field($table->integer("flow_node_desc"),"节点说明");
            t_field($table->integer("flow_node_check_type"),"单人,会审,或审,抄送");
            t_field($table->integer("flow_node_check_adminid"),"审核人");
            t_field($table->string("flow_node_check_pass_next_function"),"审核通过,下一级计算方式");
            $table->unique( ["flow_type", "flow_node_type_id"] );
        });

        Schema::table('db_weiyi_admin.t_flow', function( Blueprint $table)
        {
            t_field($table->string("field_list",4096),"申请数据消息,json");
        });

        Schema::table('db_weiyi_admin.t_flow_node', function( Blueprint $table)
        {
            t_field($table->integer("flow_admin_type"),"审查人的类型:审查,抄送");
            t_field($table->integer("pre_flow_nodeid"),"上一个审查节点");
        });

        Schema::create('db_weiyi_admin.t_flow_node_adminid', function( Blueprint $table)
        {
            t_field($table->integer("flow_nodeid"),"审查id");
            t_field($table->integer("flow_node_adminid"),"审批人");
            t_field($table->integer("flow_check_flag"),"审批状态, 未审, 通过, 不通过,驳回,转审 ");
            t_field($table->integer("check_time"),"审批 时间");
            t_field($table->string ("check_msg"),"审批消息");
        });
        */

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
