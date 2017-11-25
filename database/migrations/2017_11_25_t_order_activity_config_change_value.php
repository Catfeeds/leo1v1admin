<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderActivityConfigChangeValue extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {


        Schema::table('db_weiyi.t_order_activity_config', function (Blueprint $table){

            t_field($table->integer('need_spec_require_flag')->default(0),"是否需要特殊申请 0:不需要 1:需要");

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