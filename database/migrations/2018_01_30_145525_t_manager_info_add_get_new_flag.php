<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TManagerInfoAddGetNewFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            t_field($table->tinyInteger("get_new_flag"),"0禁止抢新1不禁止抢新");
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
