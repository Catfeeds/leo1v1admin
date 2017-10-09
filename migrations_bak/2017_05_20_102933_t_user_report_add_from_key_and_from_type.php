<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TUserReportAddFromKeyAndFromType extends Migration
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
            t_field($table->integer("from_key_int"),"");
            t_field($table->integer("from_type"),"");
            $table->unique(["from_key_int","from_type"],"from_key_int_unique");
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
