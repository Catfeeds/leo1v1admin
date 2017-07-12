<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFulltimeTeacherAssessmentListAddOrderPer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_fulltime_teacher_assessment_list', function( Blueprint $table)
        {
            t_field($table->string("order_per",20),"转化率");            
            t_field($table->integer("stu_num"),"常规学生数");            
            t_field($table->integer("lesson_level"),"家长评价星级");            
            t_field($table->integer("stu_lesson_total"),"常规学生总课时数");            
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
