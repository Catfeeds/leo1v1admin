<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherInfoAddTeacherRefType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_info', function( Blueprint $table)
        {
            t_field($table->tinyInteger("teacher_ref_type"),"老师推荐人类型 0 无分类 1 廖祝佳工作室 2 王菊香工作室");
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
