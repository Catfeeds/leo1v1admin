<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTTeacherLabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop('t_teacher_label');

        Schema::create('t_teacher_label', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("teacherid"),"teacherid");
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->integer("label_origin"),"1 学生试听课,2 教研试听课反馈,3面试评价");
            t_field($table->string("interaction"),"师生互动");
            t_field($table->string("class_atmos"),"课堂氛围");
            t_field($table->string("tea_standard"),"授课规范");
            t_field($table->string("tea_style"),"老师风格");
            t_field($table->integer("lessonid"),"lessonid");
            t_field($table->integer("subject"),"科目");
            t_field($table->integer("level"),"评分等级");
            t_field($table->integer("device_level"),"设备评级");            

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
