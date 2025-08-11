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
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Customer name
            $table->date('date'); // Date field
            $table->string('email')->unique(); // Unique email for the customer
            $table->string('phone', 15); // Phone numbers typically don't exceed 15 digits
            $table->string('package'); // Package details
            $table->string('photo', 300)->nullable(); // Nullable photo field
            $table->tinyInteger('status')->default(1); // Default value 1 (active), 0 for inactive
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
