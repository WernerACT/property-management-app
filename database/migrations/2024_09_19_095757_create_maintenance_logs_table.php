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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('maintenance_item_id')->constrained('maintenance_items');
            $table->enum('action', ['repaired', 'replaced'])->default('repaired');
            $table->decimal('amount', 8, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'rejected'])->default('paid');
            $table->date('date');
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
