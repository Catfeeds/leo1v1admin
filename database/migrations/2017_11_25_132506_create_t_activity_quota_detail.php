<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTActivityQuotaDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_activity_quota_detail', function (Blueprint $table) {
            t_field($table->increments('id'),"合同活动配额明细id");
            t_field($table->integer('create_time'),"添加时间");
            t_field($table->integer('market_quota'),"明细预算配额[分]");
            t_field($table->integer('order_activity_type'),"合同活动类型");
            t_field($table->string('order_activity_desc'),"合同活动描述");
            $table->unique(['create_time', 'order_activity_desc'],'time_desc');
            $table->index('order_activity_desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_activity_quota_detail');
    }
}
