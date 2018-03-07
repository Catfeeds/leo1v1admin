<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewAddTmkSignInvalid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_seller_student_new', function(Blueprint $table) {
            t_field($table->integer("tmk_sign_invalid_time"), "tmk标注无效资源时间");
            t_field($table->integer("tmk_sign_invalid_adminid"), "tmk标注无效资源人");

            $table->index('tmk_sign_invalid_time');
            $table->index('tmk_sign_invalid_adminid');
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
