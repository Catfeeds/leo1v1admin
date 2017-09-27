<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTestAbnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_test_abner', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('msg'); 
            $table->integer('grade'); 
            $table->integer('value'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_test_abner');
    }
}
