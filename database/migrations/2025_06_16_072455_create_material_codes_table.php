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
        Schema::create('material_codes', function (Blueprint $table) {
            $table->id();
            $table->string('material_code');
            $table->string('material_number');
            $table->string('routing_code');
            $table->string('routing_number');
            $table->string('manufacture_order_code');
            $table->string('manufacture_order_number');
            $table->string('working_hours');
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
        Schema::dropIfExists('material_codes');
    }
};
