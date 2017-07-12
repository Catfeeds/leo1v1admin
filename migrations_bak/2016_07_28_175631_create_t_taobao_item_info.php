<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTaobaoItemInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_taobao_item', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('cid','500');
            $table->string('open_iid','100');
            $table->string('title','500');
            $table->string('pict_url','500');
            $table->integer('price');
            $table->string('last_modified','20');
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
