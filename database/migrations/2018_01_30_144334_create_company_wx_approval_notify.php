<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyWxApprovalNotify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("db_weiyi_admin.t_company_wx_approval_notify", function(Blueprint $table) {
            t_comment($table, "企业微信-拉取数据审批流关联此条数据相关抄送审批人");

            t_field($table->integer("d_id"), "关联id(t_company_wx_approval_data表id)");
            t_field($table->string("user_id"), "关联user_id(t_company_wx_user表user_id)");

            $table->unique(["d_id", "user_id"],"user_id_key");
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
