<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTComplaintAssignInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_complaint_assign_info', function (Blueprint $table){
            t_field($table->increments("ass_id"),"");
            t_field($table->integer("complaint_id"),"投诉id");
            t_field($table->integer("assign_adminid"),"分配人id");
            t_field($table->integer("accept_adminid"),"处理人id");
            t_field($table->integer("assign_time"),"分配时间");
            t_field($table->integer("assign_flag"),"0:接受 1:驳回");
            t_field($table->string("assign_remarks"),"分配备注");

            $table->index('complaint_id');
            $table->index('accept_adminid');
            $table->index('assign_time');
            $table->index('assign_adminid' );
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
