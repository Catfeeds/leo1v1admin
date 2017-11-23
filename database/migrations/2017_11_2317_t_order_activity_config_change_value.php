<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderActivityConfigChangeValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('db_weiyi.t_order_activity_config');
        Schema::create('db_weiyi.t_order_activity_config', function (Blueprint $table){
            $table->increments('id');
            t_field($table->string('title'),"活动标题");
            t_field($table->integer('date_range_start')->nullable(),"活动日期开始时间");
            t_field($table->integer('date_range_end')->nullable(),"活动日期结束时间");
            t_field($table->integer('user_join_time_start')->nullable(),"用户加入开始时间");
            t_field($table->integer('user_join_time_end')->nullable(),"用户加入结束时间");
            t_field($table->integer('lesson_times_min')->nullable(),"活动最小课时");
            t_field($table->integer('lesson_times_max')->nullable(),"活动最大课时");
            t_field($table->integer('last_test_lesson_start')->nullable(),"最近一次试听开始时间");
            t_field($table->integer('last_test_lesson_end')->nullable(),"最近一次试听结束时间");
            t_field($table->string('grade_list')->nullable(),"适配年级区间");
            t_field($table->tinyInteger('open_flag')->nullable(),"是否手动开启活动 0:关闭 1:正式开启 2:测试开启");
            t_field($table->tinyInteger('can_disable_flag')->nullable(),"是否手动开启活动 1:可以手动关闭 0:不可手动关闭");
            t_field($table->tinyInteger('period_flag_list')->nullable(),"分期试用");
            t_field($table->tinyInteger('contract_type_list')->nullable(),"合同类型");
            t_field($table->integer('power_value')->nullable(),"优惠力度");
            t_field($table->integer('max_count')->nullable(),"最大合同数");
            t_field($table->integer('max_change_value')->nullable(),"最大修改累计值");
            t_field($table->string('max_count_activity_type_list')->nullable(),"总配额组合");
            t_field($table->integer('order_activity_discount_type')->nullable(),"优惠类型 1:按课次数打折 2:按年级打折 3:按课次数送课 4:按金额立减");
            t_field($table->string('discount_json')->nullable(),"打折活动封装的json字符串");
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
