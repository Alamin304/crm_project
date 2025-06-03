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
        Schema::create('loyalty_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enable_loyalty')->default(false);
            $table->boolean('earn_points_from_redeemable')->default(false);
            $table->json('hidden_client_groups')->nullable();
            $table->json('hidden_clients')->nullable();
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
        Schema::dropIfExists('loyalty_settings');
    }
};
