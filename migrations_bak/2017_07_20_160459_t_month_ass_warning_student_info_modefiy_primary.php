<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMonthAssWarningStudentInfoModefiyPrimary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_month_ass_warning_student_info', function( Blueprint $table)
        {
            $table->dropPrimary();
        });

        Schema::table('db_weiyi.t_month_ass_warning_student_info',function( Blueprint $table)
        {
            $table->increments("id");
            t_field($table->integer("warning_type"),"类型 1,周预警,2 累计4周预警");
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
