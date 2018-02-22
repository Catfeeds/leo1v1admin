<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddDisputeHandleTypeNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_info', function(Blueprint $table) {
            t_field($table->tinyInteger("dispute_handle_type"),"兼职老师争议处理方式");
        });
        Schema::table('db_weiyi.t_teacher_record_list', function(Blueprint $table) {
            t_field($table->tinyInteger("dispute_handle_type_record"),"兼职老师争议处理方式");
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
