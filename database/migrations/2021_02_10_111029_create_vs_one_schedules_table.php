<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVsOneSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vs_one_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->dateTime('time')->nullable(true);
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('game_master_id')->nullable(true);
            $table->bigInteger('win_count')->default(0);
            $table->bigInteger('lose_count')->default(0);
            $table->boolean('is_solo');
            $table->bigInteger('game_score')->nullable(true);
            $table->string('game_status');
            $table->string('image')->nullable(true);
            $table->string('winner')->nullable(true);
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
        Schema::dropIfExists('vs_one_schedules');
    }
}
