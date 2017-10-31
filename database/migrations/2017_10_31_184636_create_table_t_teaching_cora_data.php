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
            t_field($table->string("new_train_through_per",32),"新老师入职通过率");
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

            t_field($table->integer("new_teacher_public"),"新老师公校老师数");
            t_field($table->integer("new_teacher_college"),"新老师在校学生数");
            t_field($table->integer("new_teacher_outfit"),"新老师机构老师数");
            t_field($table->integer("appointment_num"),"面试邀约数");
            t_field($table->integer("interview_pass_num"),"面试通过数");
            t_field($table->integer("new_teacher_train_num"),"新师培训数");
            t_field($table->integer("simulated audition_num"),"模拟试听数");
            t_field($table->integer("new_teacher_train_throuth_num"),"新老师入职数");
            t_field($table->string("appointment_time",32),"面试邀约时长");
            t_field($table->string("interview_pass_time",32),"面试通过时长");
            t_field($table->string("new_teacher_train_time",32),"新师培训时长");
            t_field($table->string("simulated audition_time",32),"模拟试听时长");
            t_field($table->string("new_teacher_train_throuth_time",32),"新老师入职时长");

            t_field($table->integer("all_new_train_num"),"培训次数");
            t_field($table->string("train_part_per",32),"培训参与率");
            t_field($table->string("train_pass_per",32),"培训通过率");
            
            t_field($table->integer("set_count_all"),"月排课数");
            t_field($table->integer("set_count_top"),"精排排课数");
            t_field($table->integer("set_count_green"),"绿色通道排课数");
            t_field($table->integer("set_count_grab"),"抢课排课数");
            t_field($table->integer("set_count_normal"),"普通排课数");
            t_field($table->string("set_count_all_avg",32),"月平均排课量");
            t_field($table->string("set_count_time_avg",32),"月平均排课时长");

            t_field($table->string("set_count_all_per",32),"排课转化率");
            t_field($table->string("set_count_seller_per",32),"排课转化率（新签）");
            t_field($table->string("set_count_expand_per",32),"排课转化率（扩科）");
            t_field($table->string("set_count_change_per",32),"排课转化率（换老师）");
            t_field($table->string("set_count_top_per",32),"精排排课转化率");
            t_field($table->string("set_count_green_per",32),"绿色通道转化率");
            t_field($table->string("set_count_grab_per",32),"抢课排课转化率");
            t_field($table->string("set_count_normal_per",32),"普通排课转化率");
            t_field($table->string("grab_success_per",32),"抢课成功率");


            
            t_field($table->integer("teacher_late_num"),"老师迟到次数");
            t_field($table->integer("teacher_change_num"),"老师调课次数");
            t_field($table->integer("teacher_leave_num"),"老师请假次数");
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
