<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRefundAnalysis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi_admin.t_refund_analysis', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("orderid"),"订单id");
            t_field($table->integer("apply_time"),"应用时间");
            t_field($table->integer("configid"),"退费原因id");
            t_field($table->integer("score"),"扣分值");
            t_field($table->string( "reason",1000),"扣分原因");
            $table->unique(["orderid","apply_time","configid" ] ,"key_index");
        });


        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('db_weiyi_admin.t_refund_analysis');
        //
    }
}
