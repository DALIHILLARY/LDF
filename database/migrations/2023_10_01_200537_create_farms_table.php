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
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('coordinates')->nullable();
            $table->string('location')->nullable();
            $table->string('village')->nullable();
            $table->string('parish')->nullable();
            $table->string('zone')->nullable();
            $table->string('livestock_type')->nullable();
            $table->string('breeds')->nullable();
            $table->string('production_type')->nullable()->comment('Milk, eggs, meat, etc');
            $table->string('date_of_establishment')->nullable();
            $table->string('size')->nullable();
            $table->string('profile_picture')->nullable();
            $table->integer('number_of_workers')->nullable();
            $table->string('land_ownership')->nullable();
            $table->string('no_land_ownership_reason')->nullable();
            $table->text('general_remarks')->nullable();
            $table->unsignedInteger('owner_id')->nullable();
            $table->unsignedInteger('added_by')->nullable();
            $table->softDeletes();

            $table->foreign('added_by')->references('id')->on('admin_users');
            $table->foreign('owner_id')->references('id')->on('admin_users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('farms');
        Schema::enableForeignKeyConstraints();
    }
};
