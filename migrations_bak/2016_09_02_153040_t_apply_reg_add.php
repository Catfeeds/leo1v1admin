<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TApplyRegAdd extends Migration
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
                $table->integer("is_fre"),"是否有朋友在本公司" ) ;
            \App\Helper\Utils::comment_field(
                $table->string("fre_name",40),"介绍人姓名" ) ;

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
