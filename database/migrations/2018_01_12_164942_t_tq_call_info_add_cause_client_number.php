<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTqCallInfoAddCauseClientNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_tq_call_info', function( Blueprint $table)
        {
            t_field($table->integer("cause"),"呼叫结果");
            t_field($table->string("client_number"),"呼出座机号");
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
