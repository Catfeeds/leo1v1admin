<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TScoreInfoIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	   Schema::table('db_tool.t_scores_info', function (Blueprint $table)
       {
           $table->index(["school_type","scores_area" ,"scores_year","scores_sum"],"index_1");
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	   Schema::table('db_tool.t_scores_info', function (Blueprint $table)
       {
           $table->dropIndex("index_1");
        });

        //
    }
}
