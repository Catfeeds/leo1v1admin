<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerAndAssRecordListAddIsDoneFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_seller_and_ass_record_list', function( Blueprint $table)
        {
            t_field($table->integer("is_done_flag"),"完成标志 0 未设置,1已解决,2未解决");
            t_field($table->integer("done_time"),"解决时间");
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
