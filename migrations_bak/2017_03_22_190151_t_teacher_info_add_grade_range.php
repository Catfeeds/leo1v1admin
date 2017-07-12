<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddGradeRange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            t_field($table->integer("grade_start"),"老师擅长年级范围开始");
            t_field($table->integer("grade_end"),"老师擅长年级范围结束");
            t_field($table->integer("not_grade"),"老师不擅长的年级段");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            $table->dropColumn(["grade_start", "grade_end", "not_grade" ]);
        });
        //
    }
}
