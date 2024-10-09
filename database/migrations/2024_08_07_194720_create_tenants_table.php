<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_type_id');
            $table->foreignId('property_id');
            $table->string('display_name');
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('mobile_number');
            $table->string('office_number')->nullable();
            $table->foreignId('address_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
