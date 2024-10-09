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
        Schema::create('recurring_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');  // Reference to original transaction
            $table->string('recurring_interval');  // Daily, Weekly, Monthly
            $table->boolean('is_active')->default(true);  // Is the recurring transaction active?
            $table->date('next_run_date');  // When should the next transaction be created?
            $table->timestamps();

            // Foreign key reference to transactions
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_transactions');
    }
};
