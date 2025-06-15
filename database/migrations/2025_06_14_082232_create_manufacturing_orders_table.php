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
        Schema::create('manufacturing_orders', function (Blueprint $table) {
            $table->id();
            $table->string('product');
            $table->dateTime('deadline');
            $table->integer('quantity');
            $table->dateTime('plan_from');
            $table->string('unit_of_measure');
            $table->string('responsible');
            $table->string('bom_code');
            $table->string('reference_code');
            $table->string('routing');
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
        Schema::dropIfExists('manufacturing_orders');
    }
};
