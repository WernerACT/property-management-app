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
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn('address_id');

            // Add new address-related columns
            $table->string('street_address')->after('managing_agent_id');
            $table->string('address_line_2')->nullable()->after('street_address');
            $table->string('suburb')->after('address_line_2');
            $table->string('city')->after('suburb');
            $table->string('province')->after('city');
            $table->string('postal_code')->after('province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Add back the address_id column
            $table->foreignId('address_id')->constrained()->onDelete('cascade')->after('managing_agent_id');

            // Drop the new address-related columns
            $table->dropColumn(['street_address', 'address_line_2', 'suburb', 'city', 'province', 'postal_code']);
        });
    }
};
