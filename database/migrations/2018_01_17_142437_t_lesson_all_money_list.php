<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonAllMoneyList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_lesson_all_money_list', function( Blueprint $table)
        {
            t_comment($table,"课程-学生收入-老师支出-统计列表");
            t_field($table->increments("id"),"id");
            t_field($table->integer("lessonid"),"课程id");
            t_field($table->integer("orderid"),"合同id");
            t_field($table->integer("userid"),"学生id");
            t_field($table->integer("teacherid"),"老师id");
            t_field($table->integer("lesson_type"),"课程类型");
            t_field($table->integer("lesson_count"),"课时");
            t_field($table->integer("per_price"),"课程单价");
            t_field($table->integer("confirm_flag"),"课时确认");
            t_field($table->integer("teacher_type"),"老师类型");
            t_field($table->integer("teacher_money_type"),"老师工资类型");
            t_field($table->integer("teacher_base_money"),"老师基本收入");
            t_field($table->integer("teacher_lesson_count_money"),"老师课时收入");
            t_field($table->integer("add_time"),"添加时间");
            $table->unique(["lessonid","orderid"]);
            $table->index(["lesson_type","teacher_type","teacher_money_type"],"money_type");
            $table->index(["userid","teacherid"],"user_key");
            $table->index("add_time","add_time");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
