<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderActivityConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_order_activity_config', function (Blueprint $table){
            t_field($table->integer("id"),"活动id");
            t_field($table->string('title'),"活动标题");
            t_field($table->integer('date_range_start'),"活动日期开始时间");
            t_field($table->integer('date_range_end'),"活动日期结束时间");
            t_field($table->integer('user_join_time_srart'),"用户加入开始时间");
            t_field($table->integer('user_join_time_end'),"用户加入结束时间");
            t_field($table->integer('lesson_times_min'),"活动最小课时");
            t_field($table->integer('lesson_times_max'),"活动最大课时");
            t_field($table->integer('last_test_lesson_srart'),"最近一次试听开始时间");
            t_field($table->integer('last_test_lesson_end'),"最近一次试听结束时间");
            t_field($table->string('grade_list'),"适配年级区间");
            t_field($table->tinyInteger('open_flag'),"是否手动开启活动 0:关闭 1:正式开启 2:测试开启");
            t_field($table->tinyInteger('can_disable_flag'),"是否手动开启活动 1:可以手动关闭 0:不可手动关闭");
            t_field($table->string('period_flag_list'),"分期试用");
            t_field($table->string('contract_type_list'),"合同类型");
            t_field($table->integer('power_value'),"优惠力度");
            t_field($table->integer('max_count'),"最大合同数");
            t_field($table->integer('max_change_value'),"最大修改累计值");
            t_field($table->integer('max_count_activity_type_list'),"总配额组合");
            t_field($table->integer('order_activity_discount_type'),"优惠类型 1:按课次数打折 2:按年级打折 3:按课次数送课 4:按金额立减");
            t_field($table->string('discount_json'),"打折活动封装的json字符串");
            $table->index("id");
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
