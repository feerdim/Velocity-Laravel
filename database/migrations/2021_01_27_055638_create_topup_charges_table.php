<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopupChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topup_charges', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no');
            $table->string('status');
            $table->unsignedBigInteger('crown_package_id');
            $table->unsignedBigInteger('player_id');
            $table->string('payment_type')->nullable(true);
            $table->text('snap_token')->nullable(true);
            $table->bigInteger('grand_total')->nullable(true);
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
        Schema::dropIfExists('topup_charges');
    }
}
