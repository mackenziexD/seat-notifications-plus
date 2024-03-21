<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeatNotificationsPlusInitialDeployment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_notifications_plus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('corporation_id');
            $table->BigInteger('notification_id')->unique();
            $table->dateTime('timestamp');
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
        Schema::dropIfExists('seat_notifications_plus');
    }
}