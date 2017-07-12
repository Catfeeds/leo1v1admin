<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTSellerAndAssRecordList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_seller_and_ass_record_list', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("add_time"),"申请时间");
            t_field($table->integer("type"),"类型");
            t_field($table->integer("adminid"),"申请者");
            t_field($table->integer("userid"),"学生");
            t_field($table->integer("teacherid"),"老师");
            t_field($table->string("record_info",500),"问题反馈");
            t_field($table->string("record_info_url"),"问题反馈图片地址");
            t_field($table->integer("subject"),"科目");
            t_field($table->integer("grade"),"年级");
            t_field($table->string("textbook"),"教材版本");
            t_field($table->string("stu_request_test_lesson_demand",1024),"试听需求");
            t_field($table->string("stu_score_info"),"学生成绩");
            t_field($table->string("stu_character_info"),"学生性格");
            t_field($table->string("record_scheme",500),"处理方案");
            t_field($table->string("record_scheme_url"),"处理方案图片地址");
            t_field($table->integer("accept_adminid"),"处理人");
            t_field($table->integer("accept_time"),"处理时间");
            t_field($table->integer("is_change_teacher"),"试听后是否更换过老师");
            t_field($table->integer("tea_time"),"老师给学生的上课时长");
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
