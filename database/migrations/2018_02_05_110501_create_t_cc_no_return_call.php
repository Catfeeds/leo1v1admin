<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTCcNoReturnCall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_cc_no_return_call', function (Blueprint $table) {
            t_comment($table, "cc未回访记录表[artisan command:cc_no_return_call]" );
            t_field($table->integer('uid'), "cc用户id");
            t_field($table->integer('no_return_call_num'), "cc未回访数量[试听成功的用户]");
            t_field($table->string('no_call_str',1200), "cc未拨打用户电话字符串");
            t_field($table->integer("add_time"), "记录时间");
            $table->unique('uid', 'uid_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_cc_no_return_call');
    }
}
