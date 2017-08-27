<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminChannelGroupAddPrimaryKeyRefType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_admin_channel_group', function( Blueprint $table)
        {
            $table->dropColumn('teacher_ref_type');
        });

        Schema::table('db_weiyi_admin.t_admin_channel_group', function( Blueprint $table)
        {
            t_field($table->integer("ref_type"),"二级招师渠道");
            $table->primary("ref_type");
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
