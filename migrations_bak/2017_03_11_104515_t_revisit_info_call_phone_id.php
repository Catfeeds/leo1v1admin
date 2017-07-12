<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRevisitInfoCallPhoneId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('db_weiyi.t_revisit_info', function( Blueprint $table)
        {
            t_field($table->integer("call_phone_id")->nullable(),
                    "呼叫电话id" );
            $table->unique( "call_phone_id" );
            $table->dropIndex("revisit_time");
            $table->index("revisit_time");
        });
        //
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
