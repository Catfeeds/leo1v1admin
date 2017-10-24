<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderidOrdernoListAddParentOrderid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_orderid_orderno_list', function( Blueprint $table)
        {
            t_field($table->integer("parent_orderid"),"主合同id");
            t_field($table->tinyInteger("pay_flag"),"是否付款");
            t_field($table->string("channel"),"付款渠道");
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
