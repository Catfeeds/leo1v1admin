<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTProductFeedbackList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("db_weiyi.t_product_feedback_list", function(Blueprint $table) {
            t_field($table->increments("id"),"产品问题记录表");
            t_field($table->integer("feedback_adminid"),"反馈人");
            t_field($table->integer("record_adminid"),"记录者");
            t_field($table->string('describe', 1024),"问题描述");
            t_field($table->string('name', 1024),"课程链接");
            t_field($table->string('reason', 256),"原因");
            t_field($table->string('solution', 1024),"解决方案");
            t_field($table->integer("student_id"),"学生id");
            t_field($table->integer("teacher_id"),"老师id");
            t_field($table->tinyInteger('deal_flag'),"是否解决 0:未解决 1:已解决");
            t_field($table->string('remark', 1024),"备注");
            t_field($table->integer("create_time"),"添加时间");

            $table->index('create_time');
            $table->index('feedback_adminid');
            $table->index('record_adminid');
            $table->index('student_id');
            $table->index('teacher_id');
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
