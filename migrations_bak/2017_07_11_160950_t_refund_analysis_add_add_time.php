<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRefundAnalysisAddAddTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_refund_analysis', function( Blueprint $table)
        {
            t_field($table->integer("add_time"),"处理时间");
            $table->index("add_time","add_time");
            $table->index("apply_time","apply_time");
            $table->index("configid","configid");
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
