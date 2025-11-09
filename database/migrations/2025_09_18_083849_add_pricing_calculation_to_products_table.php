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
            // Add pricing calculation fields
            $table->enum('pricing_method', ['percentage', 'fixed'])->default('percentage')->after('selling_price');
            $table->decimal('markup_percentage', 5, 2)->nullable()->after('pricing_method'); // e.g., 25.50 for 25.5%
            $table->decimal('fixed_selling_price', 10, 2)->nullable()->after('markup_percentage');
            $table->boolean('auto_calculate_selling_price')->default(true)->after('fixed_selling_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_method',
                'markup_percentage', 
                'fixed_selling_price',
                'auto_calculate_selling_price'
            ]);
        });
    }
};