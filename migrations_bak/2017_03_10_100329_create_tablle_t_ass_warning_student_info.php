<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablleTAssWarningStudentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_month_ass_warning_student_info', function (Blueprint $table){
            t_field($table->integer("adminid"),"助教adminid");
            t_field($table->integer("month"),"月度时间,以每月一日");
            t_field($table->integer("userid"),"userid");
            t_field($table->integer("groupid"),"分组groupid");
            t_field($table->string("group_name"),"组名");
            t_field($table->integer("left_count"),"剩余课时");
            t_field($table->integer("end_week"),"预计结课周");
            t_field($table->integer("ass_renw_flag"),"0 未设置,1 续费,2 不续费,3 联络或考虑");
            t_field($table->string("no_renw_reason"),"未续费原因");
            t_field($table->integer("renw_price"),"续费金额");
            t_field($table->integer("renw_week"),"计划续费周");
            t_field($table->integer("master_renw_flag"),"组长确认是否续费 ");
            t_field($table->string("master_no_renw_reason"),"组长确认未续费原因");
            $table->primary(["userid","month"]);
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
