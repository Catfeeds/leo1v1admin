<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMainGroupNameMonthAddMainAssignPercent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_main_group_name_month', function( Blueprint $table)
        {
            $table->dropColumn('group_assign_percent');
            t_field($table->integer("main_assign_percent"),"分配比例");
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
