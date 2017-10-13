<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TestTestLuki extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


        Schema::create('db_weiyi_admin.t_test_luki', function( Blueprint $table)
        {
            t_field($table->increments("id"),"xxx ");
            t_field($table->integer("value"),"msg xx  ");
            t_field($table->integer ("grade"),"grage x ");
            t_field($table->string("msg"),"msg xx  ");

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
