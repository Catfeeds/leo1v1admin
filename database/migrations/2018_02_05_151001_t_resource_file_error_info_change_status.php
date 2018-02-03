<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceFileErrorInfoChangeStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_resource_file_error_info', function(Blueprint $table) {
            // t_field($table->integer("resource_id"),"讲义资源id");
            // t_field($table->integer("first_check")->default(0),"初审");
            // t_field($table->integer("second_check")->default(0),"复审");
            // t_field($table->integer("first_check_adminid")->default(0),"初审人");
            // t_field($table->integer("sec_check_adminid")->default(0),"复审人");
            t_field($table->integer("first_check_time")->default(0),"初审时间");
            t_field($table->integer("second_check_time")->default(0),"复审时间");

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
