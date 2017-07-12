<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMonthAssStudentInfoAddKpiType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_month_ass_student_info', function( Blueprint $table)
        {
            $table->dropPrimary();
        });

        Schema::table('db_weiyi.t_month_ass_student_info', function( Blueprint $table)
        {
            t_field($table->integer("kpi_type"),"助教月kpi版本");
            $table->primary(["adminid","month","kpi_type"]);
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
