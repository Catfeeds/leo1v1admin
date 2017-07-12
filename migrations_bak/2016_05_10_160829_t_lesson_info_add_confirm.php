<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddConfirm extends Migration
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
            \App\Helper\Utils::comment_field( $table->integer("confirm_flag"),"课时确认   0:未确认,1:有效课程 2:无效课程," );
            \App\Helper\Utils::comment_field( $table->integer("confirm_adminid"),"课时确认人" );
            \App\Helper\Utils::comment_field( $table->integer("confirm_time"),"课时确认时间" );
            \App\Helper\Utils::comment_field( $table->string("confirm_reason"),"课时确认原因" );
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
        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            $table->dropColumn("confirm_flag");
            $table->dropColumn("confirm_adminid");
            $table->dropColumn("confirm_time");
            $table->dropColumn("confirm_reason");
        });

        //
    }
}
