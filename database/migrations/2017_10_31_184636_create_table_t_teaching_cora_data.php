<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTTeachingCoraData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teaching_core_data', function( Blueprint $table)
        {
            t_field($table->integer("time"),"时间 月/周 第一天");
            t_field($table->tinyInteger("time_type"),"1 月,2 周");
            t_field($table->integer("new_train_through_num"),"新入职老师数");
            t_field($table->integer("lesson_teacher_num"),"上课老师数");
            t_field($table->integer("new_lesson_teacher_num"),"新增上课老师数");
            t_field($table->integer("old_lesson_teacher_num"),"留存上课老师数");
            t_field($table->integer("lose_teacher_num"),"流失上课老师数");
            t_field($table->integer("lose_teacher_num_three"),"流失上课老师数(三个月)");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->string("tea_stu_per",32),"师生比");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            t_field($table->integer("read_stu_num"),"在读学生数");
            $table->primary(["orderid","period"]);
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
