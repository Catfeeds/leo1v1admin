<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerTongjiForMonthAddSellerData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_seller_tongji_for_month', function( Blueprint $table)
        {
            t_field($table->string("sellerNumData",1024) ,'存储销售各部门的人数信息 格式: {"0":{"group_name":"咨询一部","seller_num":"31"}}');
            t_field($table->integer("five_department") ,'销售五部人数');
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
