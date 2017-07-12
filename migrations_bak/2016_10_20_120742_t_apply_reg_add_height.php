<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TApplyRegAddHeight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_apply_reg',function( Blueprint $table)
        {                 
            \App\Helper\Utils::comment_field(
                $table->integer("height"),"身高");
            \App\Helper\Utils::comment_field(
                $table->string("minor",40),"民族");
            \App\Helper\Utils::comment_field(
                $table->integer("birth_type"),"1 农历, 2 公历");
            \App\Helper\Utils::comment_field(
                $table->string("gra_school",40),"毕业学校");
            \App\Helper\Utils::comment_field(
                $table->string("gra_major",40),"专业");
            \App\Helper\Utils::comment_field(
                $table->string("health_condition",20),"健康状况");
            \App\Helper\Utils::comment_field(
                $table->integer("postcodes"),"邮编");
            \App\Helper\Utils::comment_field(
                $table->integer("is_insured"),"是否已参保 1 是 0 否");
            \App\Helper\Utils::comment_field(
                $table->integer("residence_type")," 户口性质 : 1 本埠城镇 2 本埠农村 3 外埠城镇 4 外埠农村");
            \App\Helper\Utils::comment_field(
                $table->integer("join_time"),"最快入司时间");
            \App\Helper\Utils::comment_field(
                $table->string("emergency_contact_nick",20),"紧急联系人");
            \App\Helper\Utils::comment_field(
                $table->string("emergency_contact_address"),"紧急联系人地址");
            \App\Helper\Utils::comment_field(
                $table->integer("emergency_contact_phone"),"紧急联系人电话");
            \App\Helper\Utils::comment_field(
                $table->string("trial_dept",20),"试用部门");
            \App\Helper\Utils::comment_field(
                $table->string("trial_post",20),"试用岗位");
            \App\Helper\Utils::comment_field(
                $table->string("native_place",20),"籍贯");
            \App\Helper\Utils::comment_field(
                $table->integer("trial_start_time"),"试用期开始时间");
            \App\Helper\Utils::comment_field(
                $table->integer("trial_end_time"),"试用期结束时间");
            \App\Helper\Utils::comment_field(
                $table->string("photo"),"照片");

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
