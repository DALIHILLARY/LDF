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
        Schema::create('cooperatives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->string('location')->nullable();
            $table->string('village')->nullable();
            $table->string('parish')->nullable();
            $table->string('zone')->nullable();
            $table->string('production_type')->nullable();
            $table->string('date_of_establishment')->nullable();
            $table->string('certification')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('applicant_id')->nullable();
            $table->string('status')->default('pending');
            $table->foreign('applicant_id')->references('id')->on('admin_users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperatives');
    }
};
