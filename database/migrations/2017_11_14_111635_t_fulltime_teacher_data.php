<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFulltimeTeacherData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_fulltime_teacher_data', function( Blueprint $table)
        {
            t_field($table->increments('id'),"全职老师信息存档");
            t_field($table->integer("create_time") ,"时间");
            t_field($table->integer("teacher_type") ,"0全部-1上海-2武汉");
            t_field($table->string("time_range") ,"时间范围");
            t_field($table->integer("student_num") ,"所带学生数");
            t_field($table->integer("lesson_count"),"课耗");
            t_field($table->integer("cc_transfer_per"),"CC转化率");
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
