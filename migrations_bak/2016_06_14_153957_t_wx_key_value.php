<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TWxKeyValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	   Schema::create('db_weiyi_admin.t_wx_key_value', function (Blueprint $table)
       {
           $table->integer("id",true);
           $table->string("data",8192);
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
        Schema::drop('db_weiyi_admin.t_wx_key_value');
    }
}
