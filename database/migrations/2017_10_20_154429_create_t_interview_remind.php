<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTInterviewRemind extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi_admin.t_interview_remind', function (Blueprint $table) {
            $table->increments('id');
                t_field($table->integer("hr_adminid"),"设置提醒任务人-HR");
                t_field($table->integer("interviewer_id"),"面试官id");
                t_field($table->string("name"),"应聘人姓名");
                t_field($table->string("post"),"面试岗位");
                t_field($table->integer("interview_time"),"面试时间");
                t_field($table->string("dept"),"面试部门");
                t_field($table->tinyInteger("is_send_flag"),"是否发送提醒 0:未发送 1:已发送");
                t_field($table->integer("send_msg_time"),"信息发送时间");
                $table->index('name');
                $table->index('interview_time');
                $table->index('send_msg_time');
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
