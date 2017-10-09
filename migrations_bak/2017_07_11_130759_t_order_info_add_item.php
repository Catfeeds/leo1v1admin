<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderInfoAddItem extends Migration
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
            t_field($table->integer("applicant"),"申请人");
            t_field($table->integer("app_time"),"申请时间");
            t_field($table->string("main_send_admin",50),"邮件发送人");
            t_field($table->string("mail_send_time",100),"邮件发送时间");
            t_field($table->string("mail_code",255),"运单号");
            t_field($table->string("mail_code_url",255),"运单号截图");
            t_field($table->integer("is_send_flag"),"是否邮寄合同");
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
