<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTChangeTeacherList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_change_teacher_list', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("add_time"),"申请时间");
            t_field($table->integer("ass_adminid"),"申请者");
            t_field($table->integer("userid"),"学生");
            t_field($table->integer("teacherid"),"当前老师");
            t_field($table->string("change_reason",500),"申请原因");
            t_field($table->string("except_teacher",500),"期望老师");
            t_field($table->integer("subject"),"科目");
            t_field($table->integer("grade"),"年级");
            t_field($table->string("textbook"),"教材版本");
            t_field($table->string("phone_location",64),"所在地区");
            t_field($table->string("stu_score_info"),"学生成绩");
            t_field($table->string("stu_character_info"),"学生性格");
            t_field($table->string("record_teacher"),"推荐的老师信息");
            t_field($table->string("accept_reason"),"驳回理由");
            t_field($table->integer("accept_flag"),"是否接受申请");
            t_field($table->integer("accept_adminid"),"申请处理人");
            t_field($table->integer("accept_time"),"申请处理时间");
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
