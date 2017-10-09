<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCsProposalInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_cs_proposal_info', function (Blueprint $table){
              t_field($table->integer("id",true),"id");
              t_field($table->integer("create_time"),"创建时间");
              t_field($table->integer("create_adminid"),"添加客服ID");
              t_field($table->string("username"),"姓名");
              t_field($table->string("phone"),"联系方式");
              t_field($table->integer("complaint_user_type"),"身份");
              t_field($table->string("content"),"建议内容");
              t_field($table->integer("operator"),"处理人");
              t_field($table->integer("assign_time"),"分配时间");
              $table->index("create_adminid");
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
