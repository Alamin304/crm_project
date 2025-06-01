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
        Schema::create('file_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_section_id')->constrained()->onDelete('cascade'); // Belongs to Section
            $table->foreignId('parent_id')->nullable()->constrained('file_items')->onDelete('cascade'); // For nested folders
            $table->enum('type', ['folder', 'file']);
            $table->string('name');
            $table->string('file_path')->nullable();
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
        Schema::dropIfExists('file_items');
    }
};
