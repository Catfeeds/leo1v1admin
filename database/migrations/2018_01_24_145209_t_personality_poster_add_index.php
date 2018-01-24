<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TPersonalityPosterAddIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_tool.t_personality_poster', function(Blueprint $table) {
            $table->index('uid', 'uid');
                $table->dropColumn('par_openid');
                $table->dropColumn('phone');
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
