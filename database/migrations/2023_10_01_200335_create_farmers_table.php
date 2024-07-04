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
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->string('profile_picture')->nullable();
            $table->string('surname');
            $table->string('given_name');
            $table->date('date_of_birth')->nullable();
            $table->string('nin')->nullable();
            $table->string('location')->nullable();
            $table->string('village')->nullable();
            $table->string('parish')->nullable();
            $table->string('zone')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->integer('number_of_dependants')->nullable()->comment('Number of dependants / other farmers');
            $table->string('farmer_group')->nullable();
            $table->string('primary_phone_number')->nullable();
            $table->string('secondary_phone_number')->nullable();
            $table->boolean('is_land_owner')->default(false);
            $table->string('land_ownership')->nullable();
            $table->string('production_scale')->nullable();
            $table->boolean('access_to_credit')->default(false);
            $table->string('credit_institution')->nullable();
            $table->string('date_started_farming')->nullable();
            $table->string('highest_level_of_education')->nullable();
            $table->unsignedInteger('added_by');
            $table->unsignedInteger('user_id');
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('admin_users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('added_by')->references('id')->on('admin_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('farmers');
        Schema::enableForeignKeyConstraints();
    }
};
