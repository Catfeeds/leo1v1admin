<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}

class TAdminGroupNameAddAssignPercent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_admin_group_name', function( Blueprint $table)
        {
            add_field($table->string("group_assign_percent",20),"分配比例");
        });
        Schema::table('db_weiyi_admin.t_admin_group_user', function( Blueprint $table)
        {
            add_field($table->string("assign_percent",20),"分配比例");
        });
        Schema::table('db_weiyi_admin.t_admin_main_group_name', function( Blueprint $table)
        {
            add_field($table->string("main_assign_percent",20),"分配比例");
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
