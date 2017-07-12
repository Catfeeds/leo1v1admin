<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminGroupMonthTimeUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop('db_weiyi_admin.t_admin_group_month_time');
        Schema::create('db_weiyi_admin.t_admin_group_month_time', function (Blueprint $table)
        {
            $table->integer("groupid");
            $table->string("month",20);
            $table->string("month_time",5000);
            
            $table->primary(["groupid","month"]);

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
