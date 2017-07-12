<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTUserOrginInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_origin_info',function(Blueprint $table)
        {
            $table->integer('id',true);
            $table->string('name',32);
            $table->string('phone',16);
            $table->string('origin',32);
            $table->integer('add_time')->default(0);
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
