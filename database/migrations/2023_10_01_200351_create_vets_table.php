<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vets', function (Blueprint $table) {
            $table->id();
            $table->string('profile_picture')->nullable();
            $table->string('title');
            $table->string('category')->nullable()->comment('Paravet or Vet');
            $table->string('surname');
            $table->string('given_name');
            $table->string('nin')->nullable();
            $table->string('coordinates')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->string('village')->nullable();
            $table->string('parish')->nullable();
            $table->string('zone')->nullable();
            $table->string('group_or_practice')->nullable();
            $table->string('license_number')->nullable();
            $table->string('license_expiry_date')->nullable();
            $table->string('date_of_registration')->nullable();
            $table->text('brief_profile')->nullable();
            $table->string('primary_phone_number')->nullable();
            $table->string('secondary_phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('postal_address')->nullable();
            $table->text('services_offered')->nullable();
            $table->string('ares_of_operation')->nullable();
            $table->string('certificate_of_registration')->nullable();
            $table->string('license')->nullable();
            $table->json('other_documents')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vets');
    }
};
