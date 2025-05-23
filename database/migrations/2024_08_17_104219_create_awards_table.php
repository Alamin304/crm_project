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
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('gift');
            $table->date('date');
            $table->unsignedBigInteger('employee_id'); // Add this line for the foreign key
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->unsignedBigInteger('award_by')->nullable(); // Add this line for the foreign key
            $table->foreign('award_by')->references('id')->on('employees');
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
        Schema::dropIfExists('awards');
    }
};
