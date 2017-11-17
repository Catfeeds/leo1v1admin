<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerLevelSalary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_seller_level_salary', function (Blueprint $table){
            t_field($table->integer("seller_level",true),"cc等级");
            t_field($table->integer("define_date"),"定义月份");
            t_field($table->integer("base_salary"),"基本工资");
            t_field($table->integer("sup_salary"),"保密津贴");
            t_field($table->integer("per_salary"),"绩效工资");
            t_field($table->integer("create_time"),"创建时间");
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
