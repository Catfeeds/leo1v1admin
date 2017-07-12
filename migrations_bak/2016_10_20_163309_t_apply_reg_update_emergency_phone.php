<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TApplyRegUpdateEmergencyPhone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_apply_reg', function (Blueprint $table) {
            $table->dropColumn('emergency_contact_phone');
        });
        Schema::table('db_weiyi_admin.t_apply_reg', function (Blueprint $table) {
            \App\Helper\Utils::comment_field(
                $table->string("emergency_contact_phone",40),"紧急联系人电话");

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
