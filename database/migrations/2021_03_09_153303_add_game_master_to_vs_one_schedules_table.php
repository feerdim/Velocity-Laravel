<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGameMasterToVsOneSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vs_one_schedules', function (Blueprint $table) {
            $table->boolean('is_game_master')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vs_one_schedules', function (Blueprint $table) {
            $table->dropColumn('is_game_master');
        });
    }
}
