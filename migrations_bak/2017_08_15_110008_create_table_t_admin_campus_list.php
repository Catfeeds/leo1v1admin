<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAdminCampusList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_admin_campus_list', function (Blueprint $table){
            t_field($table->integer("campus_id",true),"校区id");
            t_field($table->integer("campus_name"),"校区名称");
        });

        Schema::table('db_weiyi_admin.t_admin_main_group_name', function( Blueprint $table)
        {
            t_field($table->integer("campus_id"),"校区id");
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
