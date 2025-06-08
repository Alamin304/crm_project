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
        Schema::create('depreciations', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name');
            $table->string('serial_no')->nullable();
            $table->string('depreciation_name');
            $table->integer('number_of_month');
            $table->enum('status', ['ready', 'pending', 'undeployable', 'archive', 'operational', 'non-operational', 'repairing']);
            $table->date('checked_out')->nullable();
            $table->date('purchase_date');
            $table->date('EOL_date');
            $table->decimal('cost', 10, 2);
            $table->decimal('maintenance', 10, 2)->nullable();
            $table->decimal('current_value', 10, 2);
            $table->decimal('monthly_depreciation', 10, 2);
            $table->decimal('remaining', 10, 2);
            $table->string('image')->nullable();
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
        Schema::dropIfExists('depreciations');
    }
};
