<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceFileErrorInfoModifyStatus extends Migration
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
            $table->dropColumn('first_check');
                $table->dropColumn('second_check');
                $table->dropColumn('sec_check_adminid');
                t_field($table->integer("second_check_adminid")->default(0),"复审人");
                t_field($table->integer("reupload_adminid")->default(0),"重传人");
                t_field($table->integer("reupload_time")->default(0),"重传时间");

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
