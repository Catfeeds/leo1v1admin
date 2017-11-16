<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAdminCorporateIncomeList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('db_weiyi_admin.t_admin_corporate_income_list');
        Schema::create('db_weiyi_admin.t_admin_corporate_income_list', function (Blueprint $table) {
            t_field($table->integer('month'),"月份");
            t_field($table->integer('new_order_money'),"新签收入");
            t_field($table->integer('renew_order_money'),"续费收入");
            t_field($table->integer('new_order_stu'),"新签人数");
            t_field($table->integer('renew_order_stu'),"续费人数");
            t_field($table->integer('new_signature_price'),"新签客单价格");
            t_field($table->integer('renew_signature_price'),"续费客单价格");
            $table->primary("month");
        });

        Schema::dropIfExists('db_weiyi_admin.t_admin_refund_order_list');
        Schema::create('db_weiyi_admin.t_admin_refund_order_list', function (Blueprint $table) {
            t_field($table->increments('id'),"退费合同信息表");
            t_field($table->string('nick',32),"学生姓名");
            t_field($table->string('phone',16),"手机号");
            t_field($table->string('grade',16),"年级");
            t_field($table->string('order_custom',128),"合同备注信息");
            t_field($table->string('sys_operator',32),"下单人");
            t_field($table->integer('order_time'),"合同时间");
            t_field($table->string('contract_type',16),"合同类型");
            t_field($table->integer('lesson_total'),"合同总课时");
            t_field($table->integer('refund_lesson_count'),"应退课时");
            t_field($table->integer('order_cost_price'),"合同原价");
            t_field($table->integer('refund_price'),"实退金额");
            t_field($table->integer('order_price'),"合同实付价格");
            t_field($table->string('is_invoice',16),"是否需要发票");
            t_field($table->string('invoice',128),"发票信息");
            t_field($table->string('payment_account_id',128),"支付帐号");
            t_field($table->text('refund_info'),"退费原因");
            t_field($table->text('save_info'),"挽单结果");
            t_field($table->string('apply_account',32),"申请人");
            t_field($table->integer('apply_time'),"申请时间");
            t_field($table->integer('approve_time'),"审批时间");
            t_field($table->string('approve_status',32),"审批状态");
            t_field($table->string('refund_status',32),"退费状态");
            t_field($table->string('period_flag',16),"是否分期");
            t_field($table->string('assistant_name',32),"助教");
            t_field($table->string('subject',16),"科目");
            t_field($table->string('teacher_realname',32),"老师名字");
            t_field($table->string('connection_state',32),"联系状态");
            t_field($table->string('lifting_state',32),"提升状态");
            t_field($table->string('learning_attitude',32),"学习态度");
            t_field($table->string('order_three_month_flag',16),"下单是否超过三个月");
            t_field($table->string('assistant_one_level_cause',128),"助教部一级原因");
            t_field($table->string('assistant_two_level_cause',128),"助教部二级原因");
            t_field($table->string('assistant_three_level_cause',128),"助教部三级原因");
            t_field($table->integer('assistant_deduction_value'),"助教部扣分值");
            t_field($table->string('assistant_cause_rate',16),"助教部责任比例");
            t_field($table->text('assistant_cause_analysis'),"助教部原因分析");
           
            t_field($table->string('registrar_one_level_cause',128),"教务部一级原因");
            t_field($table->string('registrar_two_level_cause',128),"教务部二级原因");
            t_field($table->string('registrar_three_level_cause',128),"教务部三级原因");
            t_field($table->integer('registrar_deduction_value'),"教务部扣分值");
            t_field($table->string('registrar_cause_rate',16),"教务部责任比例");
            t_field($table->text('registrar_cause_analysis'),"教务部原因分析");
            t_field($table->string('teacher_manage_one_level_cause',128),"老师管理一级原因");
            t_field($table->string('teacher_manage_two_level_cause',128),"老师管理二级原因");
            t_field($table->string('teacher_manage_three_level_cause',128),"老师管理三级原因");
            t_field($table->integer('teacher_manage_deduction_value'),"老师管理扣分值");
            t_field($table->string('teacher_manage_cause_rate',16),"老师管理责任比例");
            t_field($table->text('teacher_manage_cause_analysis'),"老师管理原因分析");
            t_field($table->string('dvai_one_level_cause',128),"教学部一级原因");
            t_field($table->string('dvai_two_level_cause',128),"教学部二级原因");
            t_field($table->string('dvai_three_level_cause',128),"教学部三级原因");
            t_field($table->integer('dvai_deduction_value'),"教学部扣分值");
            t_field($table->string('dvai_cause_rate',16),"教学部责任比例");
            t_field($table->text('dvai_cause_analysis'),"教学部原因分析");
            t_field($table->string('product_one_level_cause',128),"产品部一级原因");
            t_field($table->string('product_two_level_cause',128),"产品部二级原因");
            t_field($table->string('product_three_level_cause',128),"产品部三级原因");
            t_field($table->integer('product_deduction_value'),"产品部扣分值");
            t_field($table->string('product_cause_rate',16),"产品部责任比例");
            t_field($table->text('product_cause_analysis'),"产品部原因分析");
            t_field($table->string('advisory_one_level_cause',128),"咨询部一级原因");
            t_field($table->string('advisory_two_level_cause',128),"咨询部二级原因");
            t_field($table->string('advisory_three_level_cause',128),"咨询部三级原因");
            t_field($table->integer('advisory_deduction_value'),"咨询部扣分值");
            t_field($table->string('advisory_cause_rate',16),"咨询部责任比例");
            t_field($table->text('advisory_cause_analysis'),"咨询部原因分析");
            t_field($table->string('customer_changes_one_level_cause',128),"客户情况变化一级原因");
            t_field($table->string('customer_changes_two_level_cause',128),"客户情况变化二级原因");
            t_field($table->string('customer_changes_three_level_cause',128),"客户情况变化三级原因");
            t_field($table->integer('customer_changes_deduction_value'),"客户情况变化扣分值");
            t_field($table->string('customer_changes_cause_rate',16),"客户情况变化责任比例");
            t_field($table->text('customer_changes_cause_analysis'),"客户情况变化原因分析");
            t_field($table->string('teacher_one_level_cause',128),"老师一级原因");
            t_field($table->string('teacher_two_level_cause',128),"老师二级原因");
            t_field($table->string('teacher_three_level_cause',128),"老师三级原因");
            t_field($table->integer('teacher_deduction_value'),"老师扣分值");
            t_field($table->string('teacher_cause_rate',16),"老师责任比例");
            t_field($table->text('teacher_cause_analysis'),"老师原因分析");

            t_field($table->string('subject_one_level_cause',128),"科目一级原因");
            t_field($table->string('subject_two_level_cause',128),"科目二级原因");
            t_field($table->string('subject_three_level_cause',128),"科目三级原因");
            t_field($table->integer('subject_deduction_value'),"科目扣分值");
            t_field($table->string('subject_cause_rate',16),"科目责任比例");
            t_field($table->text('subject_cause_analysis'),"科目原因分析");
            t_field($table->string('other_cause'),"其他原因");
            t_field($table->text('quality_control_global_analysis'),"QC整体分析");
            t_field($table->text('later_countermeasure'),"后期应对措施");

        });
        Schema::dropIfExists('db_weiyi_admin.t_admin_student_month_info');
        Schema::create('db_weiyi_admin.t_admin_student_month_info', function (Blueprint $table) {
            t_field($table->integer('month'),"月份");
            t_field($table->integer('begin_stock'),"存量学生数期初");
            t_field($table->integer('increase_num'),"新增学生数");
            t_field($table->integer('end_num'),"结课学生数");
            t_field($table->integer('refund_num'),"退费学生数");
            t_field($table->integer('end_stock'),"存量期末学生数");
            t_field($table->integer('no_lesson_num'),"本月未排课学生数");
            t_field($table->integer('end_read_num'),"本月在读学生期末数");
            t_field($table->integer('three_end_num'),"两三(初三,高三)结课数");
            t_field($table->integer('expiration_renew_num'),"到期续费人数");
            t_field($table->integer('early_renew_num'),"提前续费人数");
            t_field($table->integer('end_renew_num'),"结课续费人数");
            t_field($table->string('actual_renew_rate',16),"实际续费率");
            t_field($table->string('actual_renew_rate_three',16),"实际续费率(扣除两三)");

            t_field($table->integer('test_chinese_num'),"试听学生-语文");
            t_field($table->integer('test_math_num'),"试听学生-数学");
            t_field($table->integer('test_english_num'),"试听学生-英语");
            t_field($table->integer('test_minor_subject_num'),"试听学生-小学科");
            t_field($table->integer('test_all_subject_num'),"试听学生(去重)合计");
            t_field($table->integer('increase_chinese_num'),"新增学生-语文");
            t_field($table->integer('increase_math_num'),"新增学生-数学");
            t_field($table->integer('increase_english_num'),"新增学生-英语");
            t_field($table->integer('increase_minor_subject_num'),"新增学生-小学科");
            t_field($table->integer('increase_all_subject_num'),"新增学生(去重)合计");
            t_field($table->string('increase_test_rate',16),"新增试听转化率");
            t_field($table->integer('read_chinese_num'),"在读学生-语文");
            t_field($table->integer('read_math_num'),"在读学生-数学");
            t_field($table->integer('read_english_num'),"在读学生-英语");
            t_field($table->integer('read_minor_subject_num'),"在读学生-小学科");
            t_field($table->integer('read_all_subject_num'),"在读学生(去重)合计");

            $table->primary("month");
        });


        // Schema::create('db_weiyi_admin.t_order_student_month_list', function (Blueprint $table) {
        //     t_field($table->integer('month'),"月份");
        //     t_field($table->string('orign',64),"渠道");
        //     t_field($table->integer('leads_num'),"leads数");
        //     t_field($table->integer('test_num'),"试听数");
          
        //     t_field($table->string('test_transfor_per',16),"试听转化率");
        //     t_field($table->string('order_transfor_per',16),"签单转化率");

        //     t_field($table->integer('order_stu_num'),"下单学生数");
           
        //     $table->primary(["month","origin"]);
        // });




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
