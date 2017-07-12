<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderInfoCheckMoneyInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_order_info', function (Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("check_money_flag"  ),
                "财务审查flag" );
            \App\Helper\Utils::comment_field(
                $table->integer("check_money_adminid"  ),
                "财务审查者" );
            \App\Helper\Utils::comment_field(
                $table->integer("check_money_time"  ),
                "财务审查时间" );
            \App\Helper\Utils::comment_field(
                $table->string("check_money_desc"  ),
                "财务审查说明" );
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

        Schema::table('t_order_info', function (Blueprint $table)
        {
            $table->dropColumn("check_money_flag");
            $table->dropColumn("check_money_adminid");
            $table->dropColumn("check_money_time");
            $table->dropColumn("check_money_desc");
        });


    }
}
