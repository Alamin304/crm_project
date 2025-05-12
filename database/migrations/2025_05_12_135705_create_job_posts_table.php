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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->foreignId('job_category_id')->constrained()->onDelete('cascade');
            $table->string('job_title');
            $table->enum('job_type', ['full_time', 'part_time', 'contract', 'temporary', 'internship']);
            $table->integer('no_of_vacancy');
            $table->date('date_of_closing');
            $table->enum('gender', ['male', 'female', 'any']);
            $table->integer('minimum_experience'); // in years
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->text('short_description');
            $table->longText('long_description');
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
        Schema::dropIfExists('job_posts');
    }
};
