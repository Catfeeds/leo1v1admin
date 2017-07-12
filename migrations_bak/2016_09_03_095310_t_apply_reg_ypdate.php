<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TApplyRegYpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi_admin.t_apply_reg', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->string("education_info",5000),"教育背景" ) ;
        });
 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('db_weiyi_admin.t_apply_reg', function (Blueprint $table) {
            $table->dropColumn('deucation_info');
        });
    }
}
