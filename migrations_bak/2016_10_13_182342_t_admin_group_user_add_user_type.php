<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminGroupUserAddUserType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_admin_group_user',function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("group_type")->default(0),"小组类型,0为普通的,1为主管级");
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
