<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}

class TAdminGroupMonthTimeAddMonthTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        /*Schema::table('db_weiyi_admin.t_seller_month_money_target', function( Blueprint $table)
        {
            add_field($table->string("month_time",5000),"每月时间安排");
        });
        Schema::table('db_weiyi_admin.t_admin_group_month_time', function( Blueprint $table)
        {
            add_field($table->string("month_time",5000),"每月时间安排");
            });*/

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
