<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTPptFileUpdateLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_ppt_file_update_log', function(Blueprint $table) {
            t_comment($table, "ppt转h5基础包文件更新日志表");
            t_field($table->increments("id"), "");
            t_field($table->integer("update_time"), "更新时间");
            t_field($table->integer("ip"), "请求方IP");

            $table->index('ip','ip');
            $table->index('update_time','update_time');
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
