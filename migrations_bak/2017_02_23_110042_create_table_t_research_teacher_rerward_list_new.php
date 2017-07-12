<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTResearchTeacherRerwardListNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop('t_research_teacher_rerward_list');
        Schema::create('t_research_teacher_rerward_list', function (Blueprint $table){
            t_field($table->integer("courseid"),"课程包id");
            t_field($table->integer("adminid"),"教研老师adminid");
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->integer("teacherid"),"签单老师id");
            t_field($table->integer("reward"),"奖励");
            $table->primary("courseid");
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
