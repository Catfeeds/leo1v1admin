<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderInfoAddOrderPartitionFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_order_info', function( Blueprint $table)
        {
            t_field($table->integer("order_partition_flag"),"是否拆分合同");
        });
        
        Schema::create('db_weiyi.t_child_order_info', function( Blueprint $table)
        {
            $table->increments("child_orderid");
            t_field($table->tinyInteger("child_order_type"),"子合同类型");
            t_field($table->tinyInteger("pay_status"),"付款状态 0,未付;1,已付");
            t_field($table->integer("add_time"),"创建时间");
            t_field($table->integer("pay_time"),"付款时间");
            t_field($table->integer("parent_orderid"),"父合同id");
            t_field($table->integer("price"),"合同金额");
            t_field($table->string("channel"),"付款渠道");
            t_field($table->string("from_orderno"),"第三方订单id");
            $table->unique("from_orderno");
            $table->index("add_time");
            $table->index("parent_orderid");                      
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
