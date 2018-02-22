<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTInvalidNumConfirm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_invalid_num_confirm', function(Blueprint $table) {
            t_comment($table, "无效号码确认表");
            t_field($table->increments("id"), "");
            t_field($table->integer("cc_confirm_time"), "CC确认时间");
            t_field($table->integer("userid"), "学生ID");
            t_field($table->integer("cc_adminid"), "确认人id");
            t_field($table->integer("cc_confirm_type"), "cc标注类型  枚举seller_student_sub_status");
            t_field($table->integer("tmk_confirm_time"), "tmk确认时间");
            t_field($table->integer("tmk_adminid"), "确认人id");
            t_field($table->tinyInteger("tmk_confirm_type"), "0:未设置 1:无效");
            t_field($table->integer("qc_confirm_time"), "QC确认时间");
            t_field($table->integer("qc_adminid"), "确认人id");
            t_field($table->tinyInteger("qc_confirm_type"), "qc标注类型");
            t_field($table->string("qc_mark",2048), "qc备注");

            $table->index('userid','userid');
            $table->index('qc_adminid','qc_adminid');
            $table->index('tmk_adminid','tmk_adminid');
            $table->index('cc_adminid','cc_adminid');
            $table->index('cc_confirm_time','cc_confirm_time');
            $table->index('tmk_confirm_time','tmk_confirm_time');
            $table->index('qc_confirm_time','qc_confirm_time');
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
