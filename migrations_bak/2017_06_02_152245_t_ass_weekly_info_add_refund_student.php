<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAssWeeklyInfoAddRefundStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_ass_weekly_info', function( Blueprint $table)
        {
            t_field($table->integer("refund_student"),"退费人数");
            t_field($table->integer("lesson_money"),"课时收入");
            t_field($table->string("lesson_count_per",20),"课时完成率");
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
