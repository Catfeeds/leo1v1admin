<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoLastRevisitedAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_student_info', function( Blueprint $table)
        {

            \App\Helper\Utils::comment_field(
                $table->integer("last_revisit_admin_time"),
                "试听未签 最后一次重入资源库时间");
            \App\Helper\Utils::comment_field(
                $table->integer("last_revisit_adminid"),
                "试听未签 最后一次重入资源库 获取人");
        });

        //
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
