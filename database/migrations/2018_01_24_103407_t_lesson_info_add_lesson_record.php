<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddLessonRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            t_field($table->integer("tea_draw"),"老师笔画数");
            t_field($table->integer("tea_voice"),"老师声音数");
            t_field($table->integer("stu_draw"),"学生笔画数");
            t_field($table->integer("stu_voice"),"学生声音数");
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
