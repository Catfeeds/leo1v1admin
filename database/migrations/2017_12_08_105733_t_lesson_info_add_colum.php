<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddColum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("db_weiyi.t_lesson_info", function(Blueprint $table) {
            t_field($table->integer('tea_late_minute'),"老师中断时间/分钟");
            t_field($table->integer('stu_late_minute'),"学生中断时间/分钟");
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
