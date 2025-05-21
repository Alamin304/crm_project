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
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('program_name');
            $table->string('training_type');
            $table->json('program_items'); // For multiple select items
            $table->integer('point');
            $table->json('departments'); // For multiple departments
            $table->string('apply_position');
            $table->text('description')->nullable();
            $table->string('staff_name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
            $table->string('attachment')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('training_mode')->nullable();
            $table->integer('max_participants')->nullable();
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
        Schema::dropIfExists('training_programs');
    }
};
