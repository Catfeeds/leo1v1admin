<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceFileAddReloadAdminid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_resource', function(Blueprint $table) {
                $table->dropColumn('reload_adminid');
                $table->dropColumn('kpi_adminid');
                $table->dropColumn('reload_status');
                $table->dropColumn('kpi_status');
        });
        Schema::table('db_weiyi.t_resource_file', function(Blueprint $table) {
                t_field($table->integer("reload_adminid"),"修改重传负责人");
                t_field($table->integer("kpi_adminid"),"kpi讲义统计负责人");
                t_field($table->integer("reload_status"),"修改重传status");
                t_field($table->integer("kpi_status"),"kpi status");
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
