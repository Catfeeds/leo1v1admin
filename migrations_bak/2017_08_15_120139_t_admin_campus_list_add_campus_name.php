<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminCampusListAddCampusName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_admin_campus_list', function ($table) {
            $table->dropColumn('campus_name');
        });
        Schema::table('db_weiyi_admin.t_admin_campus_list', function( Blueprint $table){
            t_field($table->string("campus_name",128),"校区名称");            
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
