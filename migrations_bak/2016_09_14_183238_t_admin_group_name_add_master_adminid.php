<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminGroupNameAddMasterAdminid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::table('db_weiyi_admin.t_admin_group_name', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("master_adminid")
                ,"助长id"
            );
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
