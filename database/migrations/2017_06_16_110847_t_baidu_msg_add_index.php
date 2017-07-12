<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TBaiduMsgAddIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_baidu_msg', function( Blueprint $table)
        {
            $table->index("message_type","message_type");
            $table->index("date","date");
            $table->index("push_num","push_num");
            $table->index("status","status");
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
