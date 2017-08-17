<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TConfigDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi_admin.t_config_date', function( Blueprint $table)
        {
            $table->increments("id");
            t_field($table->integer("config_date_type"),"分类");
            t_field($table->integer("config_date_sub_type"),"子分类");
            t_field($table->integer("opt_time"),"时间");
            t_field($table->integer("value"),"值");

            $table->unique(["config_date_type", "config_date_sub_type" , "opt_time" ], "date_config_time");

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
