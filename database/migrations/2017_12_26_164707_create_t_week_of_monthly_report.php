<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTWeekOfMonthlyReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_week_of_monthly_report', function (Blueprint $table) {
            t_field($table->increments('id'), "周月报记录id");
            t_field($table->integer('add_time'), "生成时间");
            t_field($table->tinyInteger("report_type"), "报告类型1周报2月报");
            t_field($table->integer('example_num'), "例子总数");
            t_field($table->integer('valid_example_num'), "有效例子");
            t_field($table->integer('called_num'), "已拨打例子");
            t_field($table->integer('valid_rate'), "有效例子占比[除去%]");
            t_field($table->integer('invalid_example_num'), "无效资源");
            t_field($table->integer('invalid_rate'), "无效例子占比[除去%]");
            t_field($table->integer('not_through_num'), "未接通");
            t_field($table->integer('not_through_rate'), "未接通例子数占比[除去%]");
            t_field($table->integer('high_num'), "高中例子");
            t_field($table->integer('high_num_rate'), "高中例子占比[除去%]");
            t_field($table->integer('middle_num'), "初中例子");
            t_field($table->integer('middle_num_rate'), "初中例子占比[除去%]");
            t_field($table->integer('primary_num'), "小学例子");
            t_field($table->integer('primary_num_rate'), "小学例子占比[除去%]");
            t_field($table->integer('wx_example_num'), "微信运营例子");
            t_field($table->integer('wx_order_count'), "微信新签订单数");
            t_field($table->integer('wx_order_all_money'), "微信新签订单金额");
            t_field($table->integer('pn_example_num'), "公众号例子");
            t_field($table->integer('pn_order_num'), "公众号签单数");
            t_field($table->integer('pn_order_money'), "公众号签单金额");
            t_field($table->integer('public_class_num'), "公开课次数");
            $table->index(['add_time','report_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_week_of_monthly_report');
    }
}
