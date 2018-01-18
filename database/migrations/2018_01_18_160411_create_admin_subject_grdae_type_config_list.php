<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSubjectGrdaeTypeConfigList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('db_weiyi.t_admin_subject_grade_type_config_list');
        Schema::create('db_weiyi.t_admin_subject_grade_type_config_list', function( Blueprint $table)
        {
            t_comment($table,"后台人员各类型科目年级权限相关配置表");
            t_field($table->integer("adminid"),"后台id");
            t_field($table->integer("config_type"),"类型 1,教务老师表权限");
            t_field($table->string("grade_list",256),"年级");
            t_field($table->string("subject_list",256),"科目");           
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->string("acc",64),"添加人");
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
