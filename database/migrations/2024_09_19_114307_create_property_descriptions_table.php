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
        Schema::create('property_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties');
            $table->integer('bedroom')->default(0);
            $table->integer('bathroom')->default(0);
            $table->integer('garage')->default(0);
            $table->integer('floor_size')->default(0);
            $table->integer('property_size')->default(0);
            $table->integer('parking')->default(0);
            $table->string('description')->nullable();
            $table->boolean('pet_friendly')->default(false);
            $table->boolean('garden')->default(false);
            $table->json('external_features')->nullable();
            $table->json('other_features')->nullable();
            $table->json('points_of_interest')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_descriptions');
    }
};
