<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTParentInfoEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	 Schema::table('t_parent_info', function (Blueprint $table)
        {
            $table->string("email");
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
	Schema::table('t_parent_info', function (Blueprint $table)
        {
            $table->dropColumn("email");
        });
    }
}
