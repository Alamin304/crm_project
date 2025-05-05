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
        Schema::table('wake_up_calls', function (Blueprint $table) {
            $table->dateTime('date')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wake_up_calls', function (Blueprint $table) {
            $table->date('date')->change(); // revert back to date only if rolled back
        });
    }
};
