<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentCallDataChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('db_weiyi.t_student_call_data');
        Schema::create('db_weiyi.t_student_call_data', function(Blueprint $table) {
            t_field($table->increments("userid"), "userid");
            t_field($table->integer("add_time"), "加入时间");
            t_field($table->integer("lesson_time"), "课程时间");
            t_field($table->integer("grade"), "年级");
            t_field($table->integer("subject"), "科目");
            t_field($table->integer("pad"), "pad类型");
            t_field($table->string("location",64), '地区');
            t_field($table->string("cor",64), "运营商");
            t_field($table->string("three_origin",64), "第三级渠道");
            t_field($table->string("two_origin",64), "第二级渠道");
            t_field($table->integer("origin_count"), "从几个渠道进入");
            t_field($table->integer("cc_called_count"), "拨通电话次数");
            t_field($table->integer("return_publish_count"), "回公海次数");
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
