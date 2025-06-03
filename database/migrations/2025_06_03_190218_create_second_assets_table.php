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
        Schema::create('second_assets', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('asset_name');
            $table->string('model');
            $table->enum('status', ['ready', 'pending', 'undeployable', 'archived', 'operational', 'non-operational', 'repairing']);
            $table->string('supplier');
            $table->date('purchase_date');
            $table->string('order_number');
            $table->decimal('purchase_cost', 10, 2);
            $table->string('location');
            $table->integer('warranty')->comment('In months');
            $table->boolean('requestable')->default(false);
            $table->boolean('for_sell')->default(false);
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->boolean('for_rent')->default(false);
            $table->decimal('rental_price', 10, 2)->nullable();
            $table->decimal('minimum_renting_price', 10, 2)->nullable();
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('second_assets');
    }
};
