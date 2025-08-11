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
        Schema::create('tbl_expense', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Date field
            $table->string('Purchase');
            $table->text('description')->nullable(); // Expense description
            $table->decimal('amount', 10, 2); // Expense amount with 2 decimal points
            $table->tinyInteger('status')->default(1); // Default value 1 (active), 0 for inactive
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_expense');
    }
};
