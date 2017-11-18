<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTRuleDetailInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_rule_detail_info', function (Blueprint $table) {
            t_field($table->increments('detail_id'),"详情id");
            t_field($table->integer('rule_id'),"规则id");
            t_field($table->integer('level'),"规则等级");
            t_field($table->string('name'),"规则名称");
            t_field($table->string('content','2000'),"规则明细");
            t_field($table->integer('deduct_marks'),"扣分");
            t_field($table->string('punish_type'),"处罚方式");
            t_field($table->string('add_punish','2000'),"附加处罚");
            t_field($table->integer('rank_num'),"排序位置");
            t_field($table->integer('create_time'),"创建时间");
            t_field($table->integer('adminid'),"创建者");

            $table->index('rule_id');
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
