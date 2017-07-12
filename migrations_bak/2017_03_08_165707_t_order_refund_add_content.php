<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderRefundAddContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_order_refund', function( Blueprint $table)
        {
            t_field($table->string("save_info","1000"),"挽单结果");
            t_field($table->string("refund_info","1000"),"退费理由");
            t_field($table->tinyInteger("has_receipt"),"是否有发票 0 没有 1 有");
            t_field($table->string("file_url"),"退费手续内容,压缩文件");
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
