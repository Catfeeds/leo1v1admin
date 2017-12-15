<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFullTeacherToPartInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::DropIfExists('db_weiyi.t_teacher_full_part_trans_info');
        Schema::create('db_weiyi.t_teacher_full_part_trans_info', function(Blueprint $table) {
            t_field($table->increments("id"), "老师全兼互转信息记录表");
            t_field($table->integer("add_time"), "添加时间");
            t_field($table->integer("teacherid"), "老师id");
            t_field($table->integer("level_before"), "全转兼前等级");
            t_field($table->integer("level_after"), "全转兼后等级");
            t_field($table->integer("teacher_money_type_before"), "全转兼前工资等级");
            t_field($table->integer("teacher_money_type_after"), "全转兼后工资等级");
            t_field($table->integer("require_adminid"), "申请人");
            t_field($table->integer("require_time"), "申请时间");
            t_field($table->string("require_reason","10000"), "申请原因");
            t_field($table->string("acc"), "审核人");
            t_field($table->integer("accept_time"), "审核时间");
            t_field($table->tinyInteger("accept_status"), "审核状态 1.未通过 2.通过");
            t_field($table->string("accept_info","10000"), "审核结果");
            t_field($table->tinyInteger("type"), "类型 1.全转兼 2.兼转全");
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
