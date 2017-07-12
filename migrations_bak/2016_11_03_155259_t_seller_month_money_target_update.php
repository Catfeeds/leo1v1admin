<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerMonthMoneyTargetUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop('db_weiyi_admin.t_seller_month_money_target');
        Schema::create('db_weiyi_admin.t_seller_month_money_target', function (Blueprint $table)
        {
            $table->integer("adminid");
            $table->string("month",20);
            $table->integer("money");
 
            $table->primary(["adminid", "month" ]);
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
