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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->string('position');
            $table->string('department');
            $table->integer('recruited_quantity');
            $table->string('working_form');
            $table->string('workplace');
            $table->decimal('starting_salary_from', 10, 2);
            $table->decimal('starting_salary_to', 10, 2);
            $table->date('from_date');
            $table->date('to_date');
            $table->text('reason');
            $table->text('job_description');
            $table->string('approver');
            $table->integer('age_from')->nullable();
            $table->integer('age_to')->nullable();
            $table->string('gender')->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('literacy');
            $table->string('seniority');
            $table->string('attachment')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('plans');
    }
};
