<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TGoodVideoSendListAddSubjectAndGrade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_good_video_send_list', function( Blueprint $table)
        {
            t_field($table->integer("subject"),"科目");
            t_field($table->integer("grade"),"年级");
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
