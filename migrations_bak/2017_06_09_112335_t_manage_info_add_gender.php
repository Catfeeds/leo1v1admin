<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TManageInfoAddGender extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            t_field($table->tinyInteger("company"),"公司 1,理优;2,博尔捷");
            t_field($table->tinyInteger("gender"),"性别");
            t_field($table->tinyInteger("education"),"学历");
            t_field($table->tinyInteger("employee_level"),"员工级别 1,员工;2,实习生");
            t_field($table->string("gra_school",100),"毕业院校");
            t_field($table->string("gra_major",100),"专业");
            t_field($table->string("identity_card",30),"身份证");
            t_field($table->integer("become_full_memeber_time"),"转正时间");
            t_field($table->integer("order_end_time"),"合同结束时间");
            t_field($table->tinyInteger("post"),"岗位");
            t_field($table->tinyInteger("department"),"部门");
            t_field($table->tinyInteger("group"),"小组");
            t_field($table->integer("basic_pay"),"基本工资");
            t_field($table->integer("merit_pay"),"绩效工资");
            t_field($table->integer("post_basic_pay"),"转正基本工资");
            t_field($table->integer("post_merit_pay"),"转正绩效工资");
            t_field($table->string("personal_email",64),"私人邮箱");
            t_field($table->string("desc"),"备注");
            
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
