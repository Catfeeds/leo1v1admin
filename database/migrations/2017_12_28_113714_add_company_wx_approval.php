<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyWxApproval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists("db_weiyi_admin.t_company_wx_approval");
        Schema::create("db_weiyi_admin.t_company_wx_approval", function(Blueprint $table) {
            t_field($table->increments("id"), "企业微信审批表");
            t_field($table->string("spname", 50), "审批名称(请假，报销，自定义审批名称)");
            t_field($table->string("apply_name", 50), "申请人姓名");
            t_field($table->string("apply_org"), "申请人部门");
            t_field($table->string("approval_name"), "审批人姓名 多人以,隔开");
            t_field($table->string("notify_name"), "抄送人姓名 多以以,隔开");
            t_field($table->tinyInteger("sp_status"), "审批状态：1审批中；2 已通过；3已驳回；4已取消；6通过后撤销；10已支付");
            t_field($table->char("sp_num", 12), "审批单号");
            t_field($table->integer("apply_time"), "审批提交时间");
            t_field($table->string("apply_user_id", 50), "审批提交者的userid");
            t_field($table->tinyInteger("type"), "申请类型 1.请假 2.报销 3.费用");
            t_field($table->tinyInteger("timeunit"), "请假时间单位 0半天 1小时");
            t_field($table->tinyInteger("approv_type"), "请假类型：1年假；2事假；3病假；4调休假；5婚假；6产假；7陪产假；8其他 报销类型：11差旅费；12交通费；13招待费；14其他报销");
            t_field($table->integer("start_time"), "请假开始时间, 费用发生事件");
            t_field($table->integer("end_time"), "请假结束时间");
            t_field($table->integer("duration"), "请假时长，单位小时");
            t_field($table->string("reason"), "请假,报销事由");
            t_field($table->string("item"), "报销,费用明细");
            t_field($table->double("sums"), "费用金额");
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
