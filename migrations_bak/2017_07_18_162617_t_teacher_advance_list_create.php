<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherAdvanceListCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_advance_list', function (Blueprint $table){
            t_field($table->integer("start_time"),"季度第一天");
            t_field($table->integer("teacherid"),"");
            t_field($table->integer("level_before"),"晋升前等级");
            t_field($table->integer("level_after"),"晋升后等级");
            t_field($table->integer("lesson_count"),"季度平均月课耗");
            t_field($table->integer("lesson_count_score"),"季度平均月课耗得分");
            t_field($table->integer("cc_test_num"),"试听课数(cc)");
            t_field($table->integer("cc_order_num"),"试听签单数(cc)");
            t_field($table->string("cc_order_per",32),"试听转化率(cc)");
            t_field($table->integer("cc_order_score"),"试听转化率得分(cc)");
            t_field($table->integer("other_test_num"),"试听课数(other)");
            t_field($table->integer("other_order_num"),"试听签单数(other)");
            t_field($table->string("other_order_per",32),"试听转化率(other)");
            t_field($table->integer("other_order_score"),"试听转化率得分(other)");
            t_field($table->integer("record_num"),"试听反馈次数");
            t_field($table->string("record_score_avg",32),"试听反馈平均得分");
            t_field($table->integer("record_final_score"),"教学质量评估分数");
            t_field($table->integer("is_refund"),"是否有退费");
            t_field($table->integer("total_score"),"总得分");          
            t_field($table->integer("require_adminid"),"申请人");          
            t_field($table->integer("require_time"),"申请时间");          
            t_field($table->integer("accept_adminid"),"审核人");          
            t_field($table->integer("accept_time"),"审核时间");          
            t_field($table->integer("accept_flag"),"审核结果");          
            t_field($table->string("accept_info"),"审核批注");
            $table->primary(["start_time","teacherid"]);
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
