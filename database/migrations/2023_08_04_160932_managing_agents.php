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
        Schema::create('managing_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('managing_agent_type_id')->constrained()->onDelete('cascade');
            $table->string('display_name');
            $table->string('name');
            $table->string('surname');
            $table->string('mobile_number');
            $table->string('office_number')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managing_agents');
    }
};
