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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('software_name');
            $table->string('category_name');
            $table->string('product_key');
            $table->integer('seats');
            $table->string('manufacturer');
            $table->string('licensed_name');
            $table->string('licensed_email');
            $table->boolean('reassignable')->default(false);
            $table->string('supplier');
            $table->string('order_number');
            $table->string('purchase_order_number');
            $table->decimal('purchase_cost', 10, 2);
            $table->date('purchase_date');
            $table->date('expiration_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('depreciation');
            $table->boolean('maintained')->default(false);
            $table->boolean('for_sell')->default(false);
            $table->decimal('selling_price', 10, 2)->nullable();
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
        Schema::dropIfExists('licenses');
    }
};
