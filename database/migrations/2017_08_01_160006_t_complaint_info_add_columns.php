<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TComplaintInfoAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_complaint_info', function( Blueprint $table)
        {
            t_field($table->integer("punish_style"),"QC投诉老师-处罚类型 1:A类 2:B类 3:C类");
            t_field($table->integer("orderid")->unsigned(),"QC投诉老师-合同id");
            t_field($table->string("apply_time"),"QC投诉老师-退费合同申请时间");
        });

        /**
           $punish_style = $this->get_in_int_val('punish_style');
           $order_id     = $this->get_in_int_val('order_id');
           $apply_time   = $this->get_in_int_str('apply_time');


         **/

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
