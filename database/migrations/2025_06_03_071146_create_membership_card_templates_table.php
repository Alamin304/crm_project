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
        Schema::create('membership_card_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_path');
            $table->boolean('show_subject_card')->default(false);
            $table->boolean('show_company_name')->default(false);
            $table->boolean('show_client_name')->default(false);
            $table->boolean('show_member_since')->default(false);
            $table->boolean('show_memberships')->default(false);
            $table->boolean('show_custom_field')->default(false);
            $table->string('text_color')->default('#000000');
            // $table->unsignedBigInteger('added_by');
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
        Schema::dropIfExists('membership_card_templates');
    }
};
