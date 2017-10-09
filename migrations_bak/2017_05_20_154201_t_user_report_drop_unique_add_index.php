<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TUserReportDropUniqueAddIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_user_report', function (Blueprint $table){
            $table->dropUnique("from_key_int_unique");
            $table->index(['log_time','from_key_int','from_type'],'user_report_id');
                // $table->index("log_time");

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
