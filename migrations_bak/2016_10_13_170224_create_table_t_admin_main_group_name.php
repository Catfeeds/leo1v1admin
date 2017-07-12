<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAdminMainGroupName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_admin_main_group_name', function (Blueprint $table)
        {
            $table->integer('groupid');
            $table->integer('main_type');
            $table->string('group_name',255);
            \App\Helper\Utils::comment_field($table->integer("master_adminid"),"负责人") ;

            $table->primary("groupid");
            $table->index("main_type");
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
