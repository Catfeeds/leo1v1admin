<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTongjiDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	   Schema::create('t_tongji_date', function (Blueprint $table)
       {
           $table->integer("log_type");
           $table->integer("id");
           $table->integer("log_time");
           $table->integer("count");
           $table->primary(["log_type","id","log_time"]);
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
