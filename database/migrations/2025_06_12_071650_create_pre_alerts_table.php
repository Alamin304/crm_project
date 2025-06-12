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
        Schema::create('pre_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('tracking')->unique();
            $table->date('date');
            $table->string('customer');
            $table->string('shipping_company');
            $table->string('supplier');
            $table->text('package_description');
            $table->date('delivery_date');
            $table->decimal('purchase_price', 10, 2);
            $table->enum('status', ['pending', 'approved'])->default('pending');
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
        Schema::dropIfExists('pre_alerts');
    }
};
