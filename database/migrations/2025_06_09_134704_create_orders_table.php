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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->date('order_date');
            $table->string('customer');
            $table->string('group_customer')->nullable();
            $table->enum('order_type', ['sale order', 'booking', 'return order']);
            $table->string('payment_method');
            $table->string('channel');
            $table->enum('status', [
                'Draft', 'Processing', 'Pending Payment', 'Paid',
                'Confirm', 'Shipping', 'Finish', 'Failed',
                'Return', 'Refund', 'Partial Refund',
                'Partial Return', 'Canceled', 'On Hold'
            ])->default('Draft');
            $table->string('invoice')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
