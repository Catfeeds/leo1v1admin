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
            t_field($table->integer("test_teacher_num"),"试听课老师数");
            t_field($table->integer("normal_teacher_num"),"常规课老师数");
            t_field($table->string("test_textbook_rate",32),"试听课学生与老师教材匹配度");
            t_field($table->string("new_train_per",32),"新老师入职通过率");
            t_field($table->string("new_train_through_time",32),"新老师入职时长");
            t_field($table->string("new_tea_thirty_stay_per",32),"新老师30天留存率");
            t_field($table->string("new_tea_sixty_stay_per",32),"新老师60天留存率");
            t_field($table->string("new_tea_ninty_stay_per",32),"新老师90天留存率");
            t_field($table->string("new_tea_thirty_tran_per",32),"新老师30天转化率");
            t_field($table->string("new_tea_sixty_tran_per",32),"新老师60天转化率");
            t_field($table->string("new_tea_ninty_tran_per",32),"新老师90天转化率");
            t_field($table->integer("new_tea_thirty_lesson_count"),"新老师30天平均课耗数");
            t_field($table->integer("new_tea_sixty_lesson_count"),"新老师60天平均课耗数");
            t_field($table->integer("new_tea_ninty_lesson_count"),"新老师90天平均课耗数");

            t_field($table->string("new_tea_thirty_stay_per",32),"新老师30天留存率");
            t_field($table->string("new_train_through_time",32),"新老师入职时长");
            t_field($table->string("new_train_through_time",32),"新老师入职时长");
            t_field($table->string("new_train_through_time",32),"新老师入职时长");
            t_field($table->string("new_train_through_time",32),"新老师入职时长");
            t_field($table->string("new_train_through_time",32),"新老师入职时长");
            t_field($table->string("new_train_through_time",32),"新老师入职时长");
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
