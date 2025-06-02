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
        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('customer_group');
            $table->string('customer');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->string('rule_base');
            $table->decimal('minimum_purchase', 10, 2)->default(0);
            $table->integer('account_creation_point')->default(0);
            $table->integer('birthday_point')->default(0);
            $table->string('redeem_type');
            $table->integer('minimum_point_to_redeem')->default(0);
            $table->decimal('max_amount_receive', 10, 2)->default(0);
            $table->boolean('redeem_in_portal')->default(false);
            $table->boolean('redeem_in_pos')->default(false);
            $table->json('rules')->nullable(); // For storing dynamic rules
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
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
        Schema::dropIfExists('loyalty_programs');
    }
};
