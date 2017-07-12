<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTOriginKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_origin_key', function (Blueprint $table) {
            $table->string('key1');
            $table->string('key2');
            $table->string('key3');
            $table->string('key4');
            $table->string('value');
            $table->unique('value');
            $table->primary(['key1', 'key2', 'key3','key4']);
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

        Schema::drop('t_origin_key');

    }
}
