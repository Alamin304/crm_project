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
        Schema::create('property_owners', function (Blueprint $table) {
            $table->id();
            $table->string('profile_image')->nullable();
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
            $table->string('facebook_url')->nullable();
            $table->string('whatsapp_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('property_owners');
    }
};
