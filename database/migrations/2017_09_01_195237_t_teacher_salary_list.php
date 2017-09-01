<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherSalaryList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_salary_list', function( Blueprint $table)
        {
            $table->primary("id");
            $table->integer("teacherid");
            $table->tinyInteger("teacher_money_type");
            $table->tinyInteger("level");
            t_field($table->string("money"),"所发工资");
            t_field($table->string("payoff_time"),"发工资时间");
            t_field($table->string("payoff_status"),"发工资状态 0 未发 1 已发");

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
