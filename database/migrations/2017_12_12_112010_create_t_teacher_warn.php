<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherWarn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('db_weiyi.t_teacher_warn', function(Blueprint $table) {
            t_field($table->increments("id"), "教师预警表");
            t_field($table->integer("teacherid"), "老师id");
            t_field($table->integer("five_num"), "迟到5分钟次数");
            t_field($table->integer("fift_num"), "迟到15分钟次数");
            t_field($table->integer("leave_num"), "离开20分钟次数");
            t_field($table->integer("absent_num"), "旷课次数");
            t_field($table->integer("adjust_num"), "调课次数");
            t_field($table->integer("ask_leave_num"), "请假次数");
            t_field($table->integer("big_order_num"), "大单数");
            t_field($table->integer("add_time"), "添加时间(同lesson_start)");
            $table->unique(['teacherid','add_time']);
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
