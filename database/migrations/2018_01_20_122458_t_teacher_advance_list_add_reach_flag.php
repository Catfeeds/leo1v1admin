<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherAdvanceListAddReachFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_advance_list', function( Blueprint $table)
        {
            t_field($table->integer("withhold_first_trial_time"),"扣款初审时间");
            t_field($table->integer("withhold_first_trial_adminid"),"扣款初审人");
            t_field($table->integer("withhold_first_trial_flag"),"扣款初审结果");
            t_field($table->integer("withhold_final_trial_time"),"扣款终审时间");
            t_field($table->integer("withhold_final_trial_adminid"),"扣款终审人");
            t_field($table->integer("withhold_final_trial_flag"),"扣款终审结果");
            t_field($table->integer("advance_first_trial_time"),"晋升初审时间");
            t_field($table->integer("advance_first_trial_adminid"),"晋升初审人");
            t_field($table->integer("advance_first_trial_flag"),"晋升初审结果");
            t_field($table->tinyInteger("reach_flag"),"达标标识");
            t_field($table->integer("withhold_money"),"扣款金额/月");
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
