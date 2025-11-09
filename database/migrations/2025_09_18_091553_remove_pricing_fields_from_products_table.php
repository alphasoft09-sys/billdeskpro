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
        Schema::table('products', function (Blueprint $table) {
            // Remove pricing-related fields since pricing is now handled by batches
            $table->dropColumn([
                'purchase_price',
                'selling_price', 
                'pricing_method',
                'markup_percentage',
                'fixed_selling_price',
                'auto_calculate_selling_price'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Re-add the pricing fields if rollback is needed
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->string('pricing_method')->default('percentage');
            $table->decimal('markup_percentage', 5, 2)->nullable();
            $table->decimal('fixed_selling_price', 10, 2)->nullable();
            $table->boolean('auto_calculate_selling_price')->default(true);
        });
    }
};