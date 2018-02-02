<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceFileErrorInfoAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_resource_file_error_info', function(Blueprint $table) {
            $table->dropColumn('error_url');
            t_field($table->string("error_picture","1024"), "错误文件链接(资料库)|文件链接(培训库)");
            t_field($table->integer("status"), "status");
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
