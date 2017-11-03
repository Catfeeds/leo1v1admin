<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRuffianActivityModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_ruffian_activity', function( Blueprint $table)
        {
            $table->dropColumn('prize_list');
            t_field($table->integer("prize_type"),"ruffian_prize_type 枚举类");
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
