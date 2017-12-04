<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddBindBankcardTimeForTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_teacher_info', function(Blueprint $table) {
            t_field($table->integer("bind_bankcard_time"), "绑定银行卡时间");
            $table->index('bind_bankcard_time');
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
