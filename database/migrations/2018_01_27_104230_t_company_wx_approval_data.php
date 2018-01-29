<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCompanyWxApprovalData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("db_weiyi_admin.t_company_wx_approval_data", function (Blueprint $table) {
            t_comment($table, "企业微信-拉取数据审批流");
            t_field($table->increments("id"),"id");
            t_field($table->string("apply_name"), "申请人");
            t_field($table->string("apply_user_id"), "申请人userid");
            t_field($table->integer("apply_time"), "申请时间");
            t_field($table->string("data_desc"), "数据描述");
            t_field($table->string("data_column"), "数据字段");
            t_field($table->string("require_reason"), "需求原因");
            t_field($table->integer("require_time"), "需求时间");
            t_field($table->string("acc"), "负责人");
            t_field($table->string("data_url"), "数据下载地址");

            $table->unique(["apply_user_id", "apply_time"],"apply_time_key");
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
