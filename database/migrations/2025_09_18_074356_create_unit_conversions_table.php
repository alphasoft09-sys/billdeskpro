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
        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('from_unit_id')->constrained('units'); // Purchase unit (e.g., Box)
            $table->foreignId('to_unit_id')->constrained('units');   // Sale unit (e.g., Piece)
            $table->decimal('conversion_factor', 10, 4); // How many pieces in 1 box
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure one conversion per product
            $table->unique(['product_id', 'from_unit_id', 'to_unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_conversions');
    }
};