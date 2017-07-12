<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTPicManageInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_pic_manage_info', function (Blueprint $table)
        {
            $table->integer('subject')->default(0);
            $table->integer('grade')->default(0);
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
        Schema::table('t_pic_manage_info', function (Blueprint $table)
        {
            $table->dropColumn("subject");
            $table->dropColumn("grade");
        });
    }
}
