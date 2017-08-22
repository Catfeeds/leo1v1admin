<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRequirementInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_requirement_info', function (Blueprint $table){
            t_field($table->integer("id",true),"需求id");
            t_field($table->string("title"),"需求名称");
            t_field($table->integer("name"),"产品名称");
            t_field($table->integer("create_adminid"),"提交人");
            t_field($table->integer("create_time"),"需求提交时间");
            t_field($table->integer("expect_time"),"期待交付时间");
            t_field($table->integer("priority"),"优先级");
            t_field($table->integer("significance"),"目前影响");
            t_field($table->string("notes"),"备注");
            t_field($table->string("statement"),"需求说明");
            t_field($table->string("content_pic"),"内容截图");

            t_field($table->string("product_solution"),"产品方案");
            t_field($table->integer("product_operator"),"产品部门处理人");
            t_field($table->string("product_phone"),"产品部门处理人联系方式");
            t_field($table->integer("product_add_time"),"产品处理开始时间");
            t_field($table->integer("product_submit_time"),"产品提交方案时间");
            t_field($table->string("product_reject"),"产品驳回原因");
            t_field($table->integer("product_reject_time"),"产品驳回时间");

            t_field($table->integer("development_operator"),"研发部门处理人");
            t_field($table->string("development_phone"),"研发部门处理人联系方式");
            t_field($table->integer("development_add_time"),"研发处理开始时间");
            t_field($table->integer("development_submit_time"),"研发处理完成时间");
            t_field($table->string("development_reject"),"研发驳回原因");
            t_field($table->integer("development_reject_time"),"研发驳回时间");

            t_field($table->integer("test_operator"),"测试部门处理人");
            t_field($table->string("test_phone"),"测试部门处理人联系方式");
            t_field($table->integer("test_add_time"),"测试处理开始时间");
            t_field($table->integer("test_submit_time"),"测试处理完成时间");
            t_field($table->string("test_reject"),"测试驳回原因");
            t_field($table->integer("test_reject_time"),"测试驳回时间");
            
            t_field($table->integer("product_status"),"处理状态");
            t_field($table->integer("development_status"),"开发状态");
            t_field($table->integer("test_status"),"测试状态");
            t_field($table->integer("status"),"流程状态");
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
