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
        Schema::create('unaccepted_assets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('asset');
            $table->string('image')->nullable();
            $table->string('serial_number')->unique();
            $table->string('checkout_for')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('unaccepted_assets');
    }
};
