<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOptTableLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_opt_table_log', function (Blueprint $table)
        {
            $table->increments("id");
            $table->Integer('opt_time');	
            $table->Integer('adminid');	
            $table->string('sql_str',4096);
            $table->Integer ('change_count');

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
        Schema::drop('t_opt_table_log');
        //
    }
}
