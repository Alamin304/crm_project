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
        Schema::create('org_charts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit_manager');
            $table->unsignedBigInteger('parent_unit')->nullable();
            $table->string('email')->nullable();
            $table->string('user_name')->nullable();
            $table->string('host')->nullable();
            $table->string('password')->nullable();
            $table->enum('encryption', ['TLS', 'SSL', 'no encryption'])->default('no encryption');
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
        Schema::dropIfExists('org_charts');
    }
};
