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
        Schema::create('employee_performances', function (Blueprint $table) {
            $table->id();

            // Employee and review info
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('review_period'); // e.g. "6 months"
            $table->string('supervisor_info'); // Name and position


            // Sections A and B (scored criteria)
            $table->json('section_a'); // e.g. criteria with ratings & comments
            $table->json('section_b');

            // Total score and classification
            $table->decimal('total_score', 5, 2)->default(0); // max 100

            // Reviewer information
            $table->string('reviewer_name')->nullable();
            $table->string('reviewer_signature')->nullable();
            $table->date('review_date')->nullable();
            $table->string('next_review_period')->nullable();

            // Employee comment
            $table->text('employee_comments')->nullable();

            // Development plan and key goals
            $table->json('development')->nullable(); // array of [area, outcome, person, start, end]
            $table->json('goals')->nullable(); // array of [goal, proposed_completion_date]

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
        Schema::dropIfExists('employee_performances');
    }
};
