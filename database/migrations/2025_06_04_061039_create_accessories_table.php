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
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->string('accessory_name');
            $table->string('category_name');
            $table->string('supplier');
            $table->string('manufacturer');
            $table->string('location')->nullable();
            $table->string('model_number')->nullable();
            $table->string('order_number')->nullable();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('min_quantity')->default(0);
            $table->boolean('for_sell')->default(false);
            $table->decimal('selling_price', 12, 2)->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('accessories');
    }
};
