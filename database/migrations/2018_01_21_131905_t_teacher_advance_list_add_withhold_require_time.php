<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherAdvanceListAddWithholdRequireTime extends Migration
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
            t_field($table->integer("withhold_require_time"),"扣款申请时间");
            t_field($table->integer("withhold_require_adminid"),"扣款申请人");          
            t_field($table->tinyInteger("advance_wx_flag"),"晋升推送标识");
            t_field($table->tinyInteger("withhold_wx_flag"),"扣款推送标识");
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
