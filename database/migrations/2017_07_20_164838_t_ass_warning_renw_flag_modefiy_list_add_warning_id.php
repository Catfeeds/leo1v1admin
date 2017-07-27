<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAssWarningRenwFlagModefiyListAddWarningId extends Migration
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
            $table->dropColumn('warnind_is');
        });

        Schema::table('db_weiyi.t_ass_warning_renw_flag_modefiy_list', function( Blueprint $table)
        {
            t_field($table->integer("warning_id"),"warning表中id");
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
