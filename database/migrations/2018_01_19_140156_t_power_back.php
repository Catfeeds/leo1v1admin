<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TPowerBack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create("db_weiyi_admin.t_power_back", function( Blueprint $table)
        {
            /*
            | groupid         | int(10)             | NO   | PRI | NULL    | auto_increment |
                | group_name      | varchar(64)         | NO   | UNI |         |                |
                | group_authority | varchar(8192)       | YES  |     | NULL    |                |
                | create_time     | int(10) unsigned    | NO   |     | NULL    |                |
                | del_flag        | tinyint(3) unsigned | NO   |     | 0       |                |
                | role_groupid    | int(11)             | NO   |     | NULL    |                |
            */

            //表注释
            t_comment($table, "分组权限备份表" );
            //字段以及注释
            t_field($table->integer("log_date") ,"备份日期");
            t_field($table->integer("groupid") ,"");
            t_field($table->string("group_name") ,"");
            t_field($table->string("group_authority",8192) ,"");
            t_field($table->integer("del_flag") ,"");
            t_field($table->integer("role_groupid") ,"");

            $table->primary(["log_date", "groupid" ]);
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
