<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTRevisitWarningOvertimeInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_revisit_warning_overtime_info', function( Blueprint $table)
        {
            $table->increments('overtime_id'); 

            t_field($table->integer("userid"),"学生id");
            t_field($table->integer("revisit_time"),"回访时间");
            t_field($table->string("sys_operator"),"回访人");

            t_field($table->integer("create_time"),"录入时间");
            t_field($table->integer("deal_time"),"预警处理时间");
            t_field($table->tinyInteger("deal_type"),"０:未处理，1:当月处理,2:非当月处理");

            $table->index("sys_operator");
            $table->index("deal_type");

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
