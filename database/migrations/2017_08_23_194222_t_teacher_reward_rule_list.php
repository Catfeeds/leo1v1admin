<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherRewardRuleList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_reward_rule_list', function( Blueprint $table)
        {
            t_field($table->integer("reward_type"),"奖金类型 1 课时奖励 2 推荐有奖 ");
            t_field($table->tinyInteger("rule_type"),"规则类型");
            t_field($table->integer("num"),"累计数量");
            t_field($table->integer("money"),"奖励金额");
            $table->primary(["reward_type","rule_type","num"],"type_key");
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
