<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAgentCash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_agent_cash', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("aid"),"agent表关联id");
            t_field($table->integer("cash"),"提现金额");
            t_field($table->integer("is_suc_flag"),"是否提现成功1成功,0失败");
            t_field($table->integer("create_time"),"创建时间");
            $table->index("aid");
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
