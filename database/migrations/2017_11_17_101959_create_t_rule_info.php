<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTRuleInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_rule_info', function (Blueprint $table) {
            t_field($table->increments('rule_id'),"规则id");
            t_field($table->string('title'),"规则标题");
            t_field($table->string('tip','3000'),"规则重要提示");
            t_field($table->integer('create_time'),"创建时间");
            t_field($table->integer('adminid'),"创建者");
        });
       //
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
