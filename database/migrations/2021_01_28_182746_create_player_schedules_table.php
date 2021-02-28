<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('game_master_id')->nullable(true);
            $table->bigInteger('win_count')->default(0);
            $table->bigInteger('lose_count')->default(0);
            $table->boolean('is_solo');
            $table->bigInteger('game_score')->nullable(true);
            $table->string('game_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_schedules');
    }
}
