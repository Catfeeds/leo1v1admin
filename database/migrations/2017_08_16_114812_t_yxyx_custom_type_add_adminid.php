<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TYxyxCustomTypeAddAdminid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_yxyx_custom_type', function( Blueprint $table)
        {
            t_field($table->integer("adminid"),"操作者id");
            t_field($table->integer("create_time"),"添加时间");
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
