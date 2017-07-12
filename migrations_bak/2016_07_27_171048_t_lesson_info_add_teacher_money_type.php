<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddTeacherMoneyType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field( $table->integer("teacher_money_type")->default(0),"老师工资类型" );
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
