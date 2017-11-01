<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeachingCoreDataAddSetCountGreenTopPer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teaching_core_data', function( Blueprint $table)
        {

            t_field($table->string("set_count_green_top_per",32),"精排/绿色通道转化率");

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
