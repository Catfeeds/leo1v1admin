<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFieldModifiedDelDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_field_modified_list', function ($table) {
            $table->dropColumn('db');
        });
        Schema::table('t_field_modified_list', function (Blueprint $table) {
            t_field($table->string("table_name"),"表");
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
