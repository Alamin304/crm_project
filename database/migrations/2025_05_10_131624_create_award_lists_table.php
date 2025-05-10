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
        Schema::create('award_lists', function (Blueprint $table) {
            $table->id();
            $table->string('award_name');
            $table->text('award_description')->nullable();
            $table->string('gift_item')->nullable();
            $table->date('date');
            $table->string('employee_name');
            $table->string('award_by');
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
        Schema::dropIfExists('award_lists');
    }
};
