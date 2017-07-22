<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAssWarningRenwFlagModefiyListAddIndex extends Migration
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
            $table->index("warning_id","warning_id");
            $table->index("add_time","add_time");
            $table->index("userid","userid");
            $table->index("adminid","adminid");
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
