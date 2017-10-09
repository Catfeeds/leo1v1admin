<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMonthAssWarningStudentInfoAddDoneFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
        Schema::table('db_weiyi.t_month_ass_warning_student_info', function( Blueprint $table)
        {
            t_field($table->integer("done_flag"),"结束标识");
            t_field($table->integer("done_time"),"结束标识生成时间");
        });

        Schema::create('db_weiyi.t_ass_warning_renw_flag_modefiy_list', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("warnind_is"),"warning表中id");
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->integer("userid"),"学生");
            t_field($table->tinyInteger("ass_renw_flag_before"),"修改前类型");
            t_field($table->tinyInteger("ass_renw_flag_cur"),"当前类型");
            t_field($table->string("no_renw_reason"),"未续费原因");
            t_field($table->integer("adminid"),"操作人");
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
