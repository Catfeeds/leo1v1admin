<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTGrabLessonLinkVisitOperation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_grab_lesson_link_visit_operation', function( Blueprint $table)
        {
            $table->increments("operationid");
            t_field($table->string("visitid"),"当次访问id ");
            t_field($table->integer("requireid"),"抢课ｉｄ");
            t_field($table->integer("create_time"),"抢课时间");
            t_field($table->integer("teacherid"),"老师ｉｄ");
            t_field($table->tinyInteger("success_flag"),"0:失败,1:成功");

            $table->index("teacherid");
            $table->index("create_time");
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
