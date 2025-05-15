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
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->string('claim_code')->unique();
            $table->string('customer');
            $table->string('invoice')->nullable();
            $table->string('product_service_name');
            $table->string('warranty_receipt_process')->nullable();
            $table->text('description')->nullable();
            $table->text('client_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('date_created')->nullable();
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
        Schema::dropIfExists('warranties');
    }
};
