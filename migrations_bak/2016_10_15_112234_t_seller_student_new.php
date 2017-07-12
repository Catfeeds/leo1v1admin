<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}


class TSellerStudentNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_seller_student_origin', function (Blueprint $table)
        {
            add_field($table->integer("userid"),"");
            add_field($table->string("origin",64),"渠道");
            add_field($table->integer("add_time"),"");
            add_field($table->integer("subject"),"科目");
            $table->primary(["userid","origin"]);
        });

        //
        Schema::create('t_seller_student_new', function (Blueprint $table)
        {
            add_field($table->integer("userid"), "");
            add_field($table->string("phone",16),"手机号");
            add_field($table->string("phone_location",64),"手机归属地");

            add_field($table->integer("seller_resource_type"), "渠道,转介绍,抢未回访,抢试听未签");

            add_field($table->integer("add_time"), "添加时间");
            add_field($table->integer("has_pad"), "0: 1 2");


            add_field($table->integer("admin_assignerid"), "分配者id");

            add_field($table->integer("sub_assign_adminid_1"), "分配到主管");
            add_field($table->integer("sub_assign_time_1"), "");



            add_field($table->integer("sub_assign_adminid_2"), "分配到组长");
            add_field($table->integer("sub_assign_time_2"), "");

            add_field($table->integer("admin_revisiterid"), "分配给组员");
            add_field($table->integer("admin_assign_time"), "分配时间");

            add_field($table->integer("next_revisit_time"), " 	下次回访时间");
            add_field($table->string("user_desc"), "备注");

            add_field($table->integer("first_revisit_time"), "第一次回访时间");

            add_field($table->integer("tq_called_flag"), "tq呼叫标志:0,1,2");
            add_field($table->integer("global_tq_called_flag"), "tq呼叫标志:0,1,2");
            add_field($table->integer("last_revisit_time"), "最后一次回访的时间");
            add_field($table->string("last_revisit_msg"), "最后一次回访的内容");
            add_field($table->integer("stu_test_ipad_flag"), "销售是否已经连线测试 ");

            add_field($table->string("stu_score_info"), "成绩情况");
            add_field($table->string("stu_character_info"), "性格特点");
            $table->primary("userid");
            $table->unique("phone");
            $table->index("add_time");
            $table->index("sub_assign_time_1");
            $table->index("sub_assign_time_2");
            $table->index("first_revisit_time");
            $table->index("next_revisit_time");
            $table->index("last_revisit_time");
        });


        Schema::create('t_test_lesson_subject', function (Blueprint $table)
        {
            add_field($table->integer("test_lesson_subject_id",true),"id");
            add_field($table->integer("require_admin_type"),"0:销售, 1:助教 ");
            add_field($table->integer("require_adminid"),"申请者");
            add_field($table->integer("userid"),"");
            add_field($table->integer("subject"),"科目");
            add_field($table->integer("grade"),"年级");
            add_field($table->integer("seller_student_status"),"无效，意向。");
            add_field($table->string("stu_request_test_lesson_demand",1024),"试听需求");
            add_field($table->integer("stu_request_test_lesson_time"),"期望上课时间");
            add_field($table->string("stu_request_test_lesson_time_info",1024),"试听时间段 ");
            add_field($table->string("stu_request_lesson_time_info",1024),"正式上课后时间段  ");
            add_field($table->string("stu_test_paper",128),"试卷");
            add_field($table->integer("tea_download_paper_time"),"老师试卷下载时间");
            add_field($table->integer("stu_test_lesson_level"),"试听内容：初级，中级，高级   ");
            add_field($table->integer("current_require_id")->nullable() ,"当前请求线索");
            $table->unique(["require_adminid", "userid", "grade"]);
            $table->unique(["current_require_id"]);
            $table->index("userid");
            $table->index("stu_request_test_lesson_time");
        });

        Schema::create('t_test_lesson_subject_require', function (Blueprint $table)
        {
            add_field($table->integer("require_id",true),"申请id" );
            add_field($table->string("origin",64),"渠道");
            add_field($table->integer("require_time"),"申请时间");
            add_field($table->integer("test_lesson_subject_id"),"");
            add_field($table->integer("accept_flag")," 0:未设置, 1:接受, 2:驳回");
            add_field($table->integer("accept_adminid"),"");
            add_field($table->integer("accept_time"),"处理时间");
            add_field($table->integer("lesson_success_flag"),"0:未设置,1:成功,2:取消");
            add_field($table->integer("test_lesson_student_status")," 排课状态 ");
            add_field($table->integer("notify_lesson_day1"),"通知1");
            add_field($table->integer("notify_lesson_day2"),"通知2");

            add_field($table->string("stu_lesson_content"),"本节课内容");
            add_field($table->string("stu_lesson_status"),"学生课堂状态");
            add_field($table->string("stu_study_status"),"学生吸收情况");
            add_field($table->string("stu_advantages"),"学生优点（不要过分）");
            add_field($table->string("stu_disadvantages"),"学生缺点");
            add_field($table->string("stu_lesson_plan",1024),"培训计划（简述）");
            add_field($table->string("stu_teaching_direction",1024),"教学方向");
            add_field($table->string("stu_textbook_info",1024),"教材及内容");
            add_field($table->string("stu_teaching_aim",1024),"教学目标");
            add_field($table->string("stu_lesson_count",1024),"大致推荐课时数");
            add_field($table->string("stu_advice",4096),"教学目标");
            add_field($table->string("current_lessonid")->nullable(),"当前lessonid");

            $table->index("require_time");
            $table->index("accept_time");
            $table->index("test_lesson_subject_id");
            $table->unique("current_lessonid");
        });

        Schema::create('t_test_lesson_subject_sub_list', function (Blueprint $table)
        {
            add_field($table->integer("lessonid"),"");
            add_field($table->integer("require_id"),"申请线索id");
            add_field($table->integer("set_lesson_adminid"),"排课人");
            add_field($table->integer("set_lesson_time"),"排课时间");
            add_field($table->integer("confirm_adminid"),"确认人");
            add_field($table->integer("confirm_time"),"确认时间");
            add_field($table->integer("success_flag"),"成功标识:0: 未设置 ,1:成功, 2失败");
            add_field($table->integer("fail_greater_4_hour_flag")," 是否上课前4小时取消 ");
            add_field($table->integer("test_lesson_fail_flag")," 1:[付] 学生未到,  2:[付] 学生设备网络出错,  3:[付]其它, 100:[不付] 课程取消,   101:[不付] 老师未到, 102:[不付] 老师原因, 103:[不付] 换时间 104:[不付] 换老师,105,排课出错   130:[不付] 其他 ");
            add_field($table->string("fail_reason"),"出错原因");
            $table->primary('lessonid');
            $table->index('set_lesson_time');
            $table->index('require_id');

        });

        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            add_field( $table->integer("lesson_del_flag"), "课程删除标识" );
        });



        //
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
