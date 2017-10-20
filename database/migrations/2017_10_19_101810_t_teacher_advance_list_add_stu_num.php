<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherAdvanceListAddStuNum extends Migration
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
            t_field($table->integer("stu_num"),"常规学生数");
            t_field($table->integer("stu_num_score"),"常规学生数对应得分");
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
