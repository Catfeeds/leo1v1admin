<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFulltimeTeacherPositiveRequireListAddRateStars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_fulltime_teacher_positive_require_list', function ($table) {
            $table->dropColumn('rate_stars_master');
        });
        Schema::table('db_weiyi.t_fulltime_teacher_positive_require_list', function( Blueprint $table)
        {
            t_field($table->tinyInteger("rate_stars"),"星级");            
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
