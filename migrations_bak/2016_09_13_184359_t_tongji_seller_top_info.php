<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTongjiSellerTopInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_tongji_seller_top_info', function (Blueprint $table)
        {
            $table->integer("tongji_type");
            $table->integer("logtime"); // (10:0)
            $table->integer("adminid");
            $table->integer("value");
            $table->integer("top_index");
            $table->integer("top_index2");
 
            $table->primary([ "tongji_type","logtime" , "adminid"], "pri" );
            $table->index([ "adminid","logtime"  ]  );
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
