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
        Schema::create('membership_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('customer_group');
            $table->string('customer');
            $table->string('card');
            $table->integer('point_from');
            $table->integer('point_to');
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
        Schema::dropIfExists('membership_rules');
    }
};
