<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TParentSendMgsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


        Schema::create('db_weiyi.t_parent_send_mgs_log', function( Blueprint $table)
        {
            $table->increments("id","id");
            t_field($table->integer("parentid"),"家长id");
            t_field($table->integer("create_time"),'执行时间');
            t_field($table->integer("is_send_flag"),'0:未发送 1:已发送');
            $table->index('parentid');
            $table->index('create_time');
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
