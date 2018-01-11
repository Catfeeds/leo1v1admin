<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoAddPhoneProvincePhoneCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_student_info', function( Blueprint $table)
        {
            t_field($table->string("phone_province",32),"省份");
            t_field($table->string("phone_city",64),"城市");
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
