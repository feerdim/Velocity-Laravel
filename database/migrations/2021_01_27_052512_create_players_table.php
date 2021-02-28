<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('avatar')->nullable(true);
            $table->bigInteger('crown')->default(0);
            $table->bigInteger('score')->default(0);
            $table->bigInteger('win_total')->default(0);
            $table->bigInteger('lose_total')->default(0);
            $table->longText('fcm_token')->nullable(true);
            $table->string('gender')->nullable(true);
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
        Schema::dropIfExists('players');
    }
}
