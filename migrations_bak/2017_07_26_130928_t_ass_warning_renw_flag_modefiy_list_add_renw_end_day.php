<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAssWarningRenwFlagModefiyListAddRenwEndDay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_ass_warning_renw_flag_modefiy_list', function( Blueprint $table)
        {
            t_field($table->integer("renw_end_day"),"续费截至日期(时间戳)");
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
