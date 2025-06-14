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
        Schema::create('work_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('working_hours');
            $table->decimal('time_efficiency', 5, 2)->default(100.00);
            $table->decimal('cost_per_hour', 10, 2);
            $table->integer('capacity');
            $table->decimal('oee_target', 5, 2)->default(85.00);
            $table->integer('time_before_prod')->comment('in minutes');
            $table->integer('time_after_prod')->comment('in minutes');
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
        Schema::dropIfExists('work_centers');
    }
};
