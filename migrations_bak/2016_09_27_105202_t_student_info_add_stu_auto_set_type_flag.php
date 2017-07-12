<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoAddStuAutoSetTypeFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_student_info',function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("is_auto_set_type_flag"),"是否系统自动更新学生类型,0系统自动,1,手动修改");
            \App\Helper\Utils::comment_field(
                $table->string("stu_lesson_stop_reason"),"学生停课原因");

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
