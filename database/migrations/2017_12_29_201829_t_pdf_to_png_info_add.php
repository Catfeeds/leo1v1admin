<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TPdfToPngInfoAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_pdf_to_png_info', function( Blueprint $table)
        {
            t_field($table->integer("origin_id") ,"老师上传PDF来源 0:老师后台上传 1:老师微信端选择 ");
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
