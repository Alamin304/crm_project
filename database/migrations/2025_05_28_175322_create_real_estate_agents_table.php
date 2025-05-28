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
        Schema::create('real_estate_agents', function (Blueprint $table) {
            $table->id();
            $table->string('profile_image')->nullable();
            $table->text('information')->nullable();
            $table->string('code')->unique();
            $table->string('owner_name');
            $table->string('address');
            $table->string('city');
            $table->string('vat_number')->nullable();
            $table->string('state')->nullable();
            $table->string('email')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone_number');
            $table->string('country')->nullable();
            $table->string('website')->nullable();
            $table->string('plan')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('whatsapp_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('privacy', ['private', 'public'])->default('public');
            $table->enum('verification_status', ['verified', 'regular'])->default('regular');
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('real_estate_agents');
    }
};
