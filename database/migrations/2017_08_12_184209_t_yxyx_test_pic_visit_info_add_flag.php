<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TYxyxTestPicVisitInfoAddFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_yxyx_test_pic_visit_info', function( Blueprint $table)
        {
            t_field($table->integer("flag"),"是否访问，0：未访问，1：已访问");
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
