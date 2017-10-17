<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRequirementInfoUpdateProductComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_requirement_info', function( Blueprint $table)
        {
            $table->dropColumn('product_comment');
        });
        Schema::table('db_weiyi.t_requirement_info', function( Blueprint $table)
        {
            t_field($table->string("product_comment",750),"产品备注");
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
