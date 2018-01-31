<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentSystemReleaseLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_seller_student_system_release_log', function(Blueprint $table) {
            //表注释
            t_comment($table, "系统自动分配例子释放日志" );
            //字段以及注释
            t_field($table->increments('id') ,"ID");
            t_field($table->integer('adminid'),"拨打cc的uid");
            t_field($table->integer('userid') ,"用户id");
            t_field($table->string('phone') ,"用户电话");
            t_field($table->integer('release_time') ,"释放时间");
            t_field($table->tinyInteger('release_reason_flag'),"参见Econfig_release_reason_flag枚举");
            $table->index('release_time', 'release_time_index');
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
