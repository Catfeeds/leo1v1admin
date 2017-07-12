<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}


class TSellerMonthTimeTargetAddLeaveAndOvertime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_seller_month_money_target', function( Blueprint $table)
        {
            add_field($table->string("leave_and_overtime",5000),"请假及加班情况");
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
