<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefundWarningForStudentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("db_weiyi.t_student_info", function(Blueprint $table) {
            t_field($table->integer("refund_warning_level"), "学员退费预警级别");
            t_field($table->string("refund_warning_reason"), "学员退费预警原因");
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
