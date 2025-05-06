<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_lists', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number');
            $table->dateTime('check_in');
            $table->dateTime('check_out');
            $table->string('arrival_from')->nullable();
            $table->string('booking_type')->nullable();
            $table->string('booking_reference')->nullable();
            $table->string('booking_reference_no')->nullable();
            $table->string('visit_purpose')->nullable();
            $table->text('remarks')->nullable();
            $table->string('room_type')->nullable();
            $table->string('room_no')->nullable();
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->boolean('booking_status')->default(true);
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
        Schema::dropIfExists('booking_lists');
    }
};
