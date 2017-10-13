<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TGrabLessonLinkVisitInfoAddTeacherid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_grab_lesson_link_visit_info', function( Blueprint $table)
        {

            t_field($table->integer("teacherid"),"老师ｉｄ");

        });

       //
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
