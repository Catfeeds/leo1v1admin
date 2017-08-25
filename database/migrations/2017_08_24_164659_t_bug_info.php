<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TBugInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('db_weiyi.t_bug_info');
        Schema::create('db_weiyi.t_bug_info', function (Blueprint $table){
            t_field($table->integer("id",true),"bug id");
            t_field($table->integer("name"),"项目分类");
            t_field($table->integer("create_adminid"),"提交人");
            t_field($table->string("create_phone"),"提交人联系方式");
            t_field($table->integer("create_time"),"bug提交时间");
            t_field($table->integer("finish_time"),"bug完成时间");
            t_field($table->integer("expect_time"),"期待修改bug时间");
            t_field($table->integer("priority"),"优先级(高级，中间，初级)");
            t_field($table->string("statement"),"bug详细说明");
            t_field($table->string("content_url"),"上传说明附件");


            t_field($table->integer("operator"),"当前bug负责人");
            t_field($table->string("operator_phone"),"bug负责人联系方式");
            t_field($table->integer("operator_add_time"),"bug负责人开始时间");
            t_field($table->integer("operator_submit_time"),"bug负责人提交时间");
            t_field($table->integer("operator_devolved_time"),"bug负责人移交时间");

            t_field($table->integer("receiver"),"移接人");
            t_field($table->string("receiver_phone"),"bug移接人联系方式");
            t_field($table->integer("receiver_add_time"),"bug移接人开始时间");
            t_field($table->integer("receiver_submit_time"),"bug移接人提交时间");
            
            
            t_field($table->integer("fix_status"),"bug状态");
            t_field($table->integer("status"),"状态");
            t_field($table->integer("del_flag"),"是否删除");
            $table->index("id");
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
