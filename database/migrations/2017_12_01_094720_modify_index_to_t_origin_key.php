<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyIndexToTOriginKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_origin_key', function (Blueprint $table) {
            //
            t_field($table->string('key0')->first(),"0级标签");
            $table->dropPrimary();
            $table->primary(['key0', 'key1','key2','key3','key4']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_origin_key', function (Blueprint $table) {
            //
        });
    }
}
