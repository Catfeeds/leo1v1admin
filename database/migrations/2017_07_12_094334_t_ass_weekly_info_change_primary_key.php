<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAssWeeklyInfoChangePrimaryKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_ass_weekly_info', function( Blueprint $table)
        {
            $table->dropPrimary();
        });

        Schema::table('db_weiyi.t_ass_weekly_info',function( Blueprint $table)
        {
            t_field($table->integer("time_type"),"时间类型 1周,2月");
            $table->increments("id");
            $table->index("adminid","adminid");
            $table->index("week","week");
            $table->index("time_type","time_type");
            $table->unique(['adminid','week','time_type'],'unique_record');
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
