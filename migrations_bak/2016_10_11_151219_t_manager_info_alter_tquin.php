<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TManagerInfoAlterTquin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            $table->dropColumn("tquin");
        });

        Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("tquin")->nullable(),
                "TQ adminid");
            $table->unique("tquin");
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
