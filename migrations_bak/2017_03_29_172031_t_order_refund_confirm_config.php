<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderRefundConfirmConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi_admin.t_order_refund_confirm_config', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("key1"),"部门");
            t_field($table->integer("key2"),"1");
            t_field($table->integer("key3"),"2");
            t_field($table->integer("key4"),"3");
            t_field($table->string( "value"),"值");
            $table->unique(["key1","key2","key3","key4"  ] ,"key_index");
        });
        //

        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("db_weiyi_admin.t_order_refund_confirm_config");
        //
    }
}
