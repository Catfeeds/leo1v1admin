<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TGarbLessonLinkOperationAddVisitid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_grab_lesson_link_visit_operation', function( Blueprint $table)
        {
            t_field($table->integer("visitid"),'当次访问ｉｄ');
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
