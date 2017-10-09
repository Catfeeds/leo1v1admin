<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTOrderidOrdernoList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_orderid_orderno_list', function (Blueprint $table){          
            t_field($table->string("order_no"),"支付订单号");
            t_field($table->integer("orderid"),"合同id");

            $table->primary("order_no");

           
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
