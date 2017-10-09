<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TComplaintInfoAddComplainedDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_complaint_info', function( Blueprint $table)
        {
            t_field($table->integer("complained_department"),"被投诉人所属部门");
            t_field($table->integer("feedback_type"),"反馈问题所属类型");
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
