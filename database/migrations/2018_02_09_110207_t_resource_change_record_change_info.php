<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceChangeRecordChangeInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_resource_change_record', function(Blueprint $table) {
                $table->dropColumn('type');
                $table->dropColumn('pre_teacherid');
                $table->dropColumn('apply_teacherid');
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
