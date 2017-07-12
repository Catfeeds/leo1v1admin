<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAdminGroupMonthTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_admin_group_month_time', function (Blueprint $table)
        {
            $table->integer("groupid");
            $table->integer("month");
            $table->string("month_time",5000);
            
            $table->primary(["groupid","month"]);

        });

        Schema::table('db_weiyi_admin.t_seller_month_money_target', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->string("month_time",5000),"每月时间安排");
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
