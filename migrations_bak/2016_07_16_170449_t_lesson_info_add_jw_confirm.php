<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddJwConfirm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field( $table->integer("jw_confirm_flag"),"教务课时确认   0:未确认,1:有效课程 2:无效课程," );
            \App\Helper\Utils::comment_field( $table->integer("jw_confirm_adminid"),"教务课时确认人" );
            \App\Helper\Utils::comment_field( $table->integer("jw_confirm_time"),"教务课时确认时间" );
            \App\Helper\Utils::comment_field( $table->string("jw_confirm_reason",255),"教务课时确认原因" );
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
