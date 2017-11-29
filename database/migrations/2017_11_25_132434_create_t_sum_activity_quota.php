<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTSumActivityQuota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_sum_activity_quota', function (Blueprint $table) {
            t_field($table->increments('id'),"合同活动总配额id");
            t_field($table->integer('create_time'),"添加时间");
            t_field($table->integer('market_quota'),"市场配额[分]");
            $table->unique('create_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_sum_activity_quota');
    }
}
