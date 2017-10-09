<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRevisitInfoAddInformationConfirm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_revisit_info', function( Blueprint $table)
        {
            t_field($table->integer("recover_time"),"复课时间");
            t_field($table->integer("revisit_path"),"回访路径");
            t_field($table->string("information_confirm"),"课前回访信息确认");
            t_field($table->string("parent_guidance_except"),"家长辅导预期");
            t_field($table->string("tutorial_subject_info"),"辅导科目情况");
            t_field($table->string("other_subject_info"),"其他科目情况");
            t_field($table->string("recent_learn_info",500),"最近学习情况");
            $table->index("recover_time","recover_time");
            $table->index("revisit_path","revisit_path");
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
