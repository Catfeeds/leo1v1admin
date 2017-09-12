<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherSwitchMoneyTypeList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_switch_money_type_list', function( Blueprint $table)
        {
            $table->increments("id");
            $table->integer("teacherid");
            t_field($table->string("realname","100"),"老师姓名");
            t_field($table->tinyInteger("teacher_money_type"),"原老师工资类型");
            t_field($table->tinyInteger("level"),"原老师工资等级");
            t_field($table->tinyInteger("new_teacher_money_type"),"调整的老师工资类型");
            t_field($table->tinyInteger("new_level"),"调整的老师工资等级");
            t_field($table->tinyInteger("batch"),"调整批次");
            t_field($table->tinyInteger("status"),"调整状态");
            t_field($table->tinyInteger("put_time"),"提出时间");
            t_field($table->tinyInteger("confirm_time"),"审核时间");
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
