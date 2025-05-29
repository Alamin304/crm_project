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
        Schema::create('buy_requests', function (Blueprint $table) {
            $table->id();
             $table->string('property_name');
            // $table->unsignedBigInteger('customer_id');
            $table->string('customer');
            $table->string('property_price')->nullable();
            $table->string('contract_amount')->nullable();
            $table->string('request_number')->unique();
            $table->boolean('inspected_property')->default(false);
            $table->integer('term');
            $table->date('start_date');
            $table->date('end_date');
            $table->datetime('date_created')->useCurrent();
            $table->json('bill_to')->nullable();
            $table->json('ship_to')->nullable();
            $table->enum('status', [
                'submitted',
                'sent',
                'waiting for approval',
                'approved',
                'declined',
                'complete',
                'expired',
                'cancelled'
            ])->default('submitted');
            $table->text('client_note')->nullable();
            $table->text('admin_note')->nullable();
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
        Schema::dropIfExists('buy_requests');
    }
};
