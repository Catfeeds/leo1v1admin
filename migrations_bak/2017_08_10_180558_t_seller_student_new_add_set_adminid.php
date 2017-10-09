<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewAddSetAdminid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_seller_student_new', function( Blueprint $table)
        {
            t_field($table->integer("first_tmk_set_valid_admind"),"tmk设置有效adminid");
            t_field($table->integer("first_tmk_set_valid_time"),"tmk设置有效时间");
            t_field($table->integer("first_tmk_set_seller_time"),"tmk有效转到cc的时间");
            t_field($table->integer("first_admin_master_adminid"),"首次分配给的主管");
            t_field($table->integer("first_admin_master_time"),"首次分配给主管的时间");
            t_field($table->integer("first_admin_revisiterid_time"),"首次分配给销售的时间");
            t_field($table->integer("first_seller_status"),"首次销售设置状态");
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
