<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTStudentCcToCr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        // Schema::drop('db_weiyi.t_student_cc_to_cr');

        
        Schema::create('db_weiyi.t_student_cc_to_cr', function( Blueprint $table)
        {
            $table->increments("id");

            t_field($table->integer("orderid"),"");
            t_field($table->integer("cc_id"),"申请人id");
            t_field($table->integer("ass_id"),"助教id");
            t_field($table->integer("post_time"),"提交时间");
            t_field($table->integer("reject_flag"),"是否驳回");
            t_field($table->integer("reject_time"),"");
            t_field($table->string("reject_info",1024),"驳回备注信息");

            t_field($table->integer("real_name"),"真实姓名");
            t_field($table->integer("gender"),"性别");
            t_field($table->integer("grade"),"年纪");
            t_field($table->integer("birth"),"生日");
            t_field($table->string("school",30),"学校");
            t_field($table->string("xingetedian",255),"性格特点");
            t_field($table->string("aihao",255),"爱好");
            t_field($table->string("yeyuanpai",255),"业余安排");
            t_field($table->string("parent_real_name",20),"家长真实姓名");
            t_field($table->string("parent_email",64),"家长邮箱");
            t_field($table->integer("relation_ship"),"关系");
            t_field($table->string("phone",20),"家长电话");
            t_field($table->integer("call_time"),"联系时间");
            t_field($table->string("addr",255),"家庭住址");
            t_field($table->string("subject_yingyu",50),"英语成绩");
            t_field($table->string("subject_yuwen",50),"语文成绩");
            t_field($table->string("subject_shuxu",50),"数学成绩");
            t_field($table->string("subject_wuli",50),"物理成绩");
            t_field($table->string("subject_huaxue",50),"化学成绩");
            t_field($table->string("class_top",50),"班级排名");
            t_field($table->string("grade_top",50),"年纪排名");
            t_field($table->string("subject_info",500),"学科情况");
            t_field($table->string("order_info",1000),"订单情况");
            t_field($table->string("teacher",64),"辅导老师");
            t_field($table->string("teacher_info",200),"老师包装信息");
            t_field($table->string("test_lesson_info",1500),"试听反馈");
            t_field($table->string("mail_addr",255),"礼包地址");
            t_field($table->integer("has_fapiao"),"开发票");
            t_field($table->string("fapai_title",125),"发票抬头");
            t_field($table->string("lesson_plan",1500),"课程安排");
            t_field($table->string("parent_other_require",1500),"家长需求");
            t_field($table->integer("except_lesson_count"),"每次课时");
            t_field($table->integer("week_lesson_num"),"每周课时");


            // $table->primary('id');
            $table->index(["cc_id"]);
            $table->index(["ass_id"]);
            $table->index(["orderid"]);
            $table->index(["post_time"]);
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
