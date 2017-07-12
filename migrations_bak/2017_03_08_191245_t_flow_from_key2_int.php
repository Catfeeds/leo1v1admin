<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFlowFromKey2Int extends Migration
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
        Schema::table('db_weiyi_admin.t_flow', function( Blueprint $table)
        {
            t_field($table->integer("from_key2_int") ,"from_key2_int");
            $table->dropIndex( "db_weiyi_admin_t_flow_flow_type_from_key_int_unique");
            $table->unique([ "flow_type" , "from_key_int" , "from_key2_int" ], "from_key_int_unique");
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
