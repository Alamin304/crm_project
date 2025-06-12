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
        Schema::create('bills_of_materials', function (Blueprint $table) {
            $table->id();
            $table->string('BOM_code')->unique();
            $table->string('product');
            $table->string('product_variant')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->string('unit_of_measure');
            $table->string('routing')->nullable();
            $table->enum('bom_type', ['manufacture', 'kit'])->default('manufacture');
            $table->string('manufacturing_readiness')->nullable();
            $table->string('consumption')->nullable();
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
        Schema::dropIfExists('bills_of_materials');
    }
};
