<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTComplaintDealInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_complaint_deal_info', function (Blueprint $table){
            t_field($table->increments("deal_id"),"");
            t_field($table->integer("complaint_id"),"投诉id");
            t_field($table->integer("deal_adminid"),"处理者id");
            t_field($table->integer("deal_time"),"处理时间");
            t_field($table->text("deal_info"),"处理说明");

            $table->index('complaint_id');
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
