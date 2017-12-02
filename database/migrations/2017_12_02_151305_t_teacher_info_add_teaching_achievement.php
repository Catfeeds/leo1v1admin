<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddTeachingAchievement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("db_weiyi.t_teaching_info", function(Blueprint $table) {
            t_field($table->tinyInteger("age"), "年龄");
            t_field($table->text("teaching_achievement"), "教学成就");
            t_field($table->text("parent_student_evaluate"), "家长/学生评价");
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
