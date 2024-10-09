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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('entity_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_status_id')->constrained()->onDelete('cascade');
            $table->foreignId('managing_agent_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('purchase_value', 15, 2);
            $table->decimal('current_value', 15, 2);
            $table->date('purchase_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
