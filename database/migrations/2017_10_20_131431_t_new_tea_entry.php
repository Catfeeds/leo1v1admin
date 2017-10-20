<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TNewTeaEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi.t_new_tea_entry', function(Blueprint $table) {
            t_field($table->increments("id"),"招师(面试,培训-模拟试听)月存档");
            t_field($table->integer("interview_pass_num"),"面试通过人数");
            t_field($table->integer("train_attend_new_tea_num"),"培训参训新师人数");
            t_field($table->integer("train_qual_new_tea_num"),"培训合格新师人数");
            t_field($table->integer("imit_listen_sched_lesson_num"),"模拟试听总排课人数");
            t_field($table->integer("imit_listen_attend_lesson_num"),"模拟试听总上课人数");
            t_field($table->integer("imit_listen_pass_lesson_num"),"模拟试听总通过人数");
            t_field($table->integer("grade"),"年级");
            t_field($table->integer("subject"),"科目");
            t_field($table->integer("identity"),"身份");
            t_field($table->integer("add_time"),"添加时间");

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
