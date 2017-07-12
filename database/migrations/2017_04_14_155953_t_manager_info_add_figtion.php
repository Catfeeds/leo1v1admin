<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TManagerInfoAddFigtion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //fingerprint

        Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            t_field($table->string("fingerprint1",4096),"指纹1");
            t_field($table->string("fingerprint2",4096),"指纹2");
        });
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
