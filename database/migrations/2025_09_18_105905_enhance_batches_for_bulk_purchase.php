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
        Schema::table('batches', function (Blueprint $table) {
            // Add fields for bulk purchase management
            $table->unsignedBigInteger('purchase_unit_id')->nullable()->after('product_id');
            $table->decimal('purchase_quantity', 10, 2)->default(1)->after('purchase_unit_id');
            $table->decimal('cost_per_sale_unit', 10, 2)->nullable()->after('purchase_quantity');
            $table->unsignedBigInteger('sale_unit_id')->nullable()->after('cost_per_sale_unit');
            $table->decimal('available_sale_quantity', 10, 2)->nullable()->after('sale_unit_id');
            $table->decimal('min_selling_price', 10, 2)->nullable()->after('available_sale_quantity');
            $table->decimal('max_selling_price', 10, 2)->nullable()->after('min_selling_price');
            $table->decimal('conversion_factor', 10, 4)->default(1)->after('max_selling_price');
            
            // Add foreign key constraints
            $table->foreign('purchase_unit_id')->references('id')->on('units')->onDelete('set null');
            $table->foreign('sale_unit_id')->references('id')->on('units')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropForeign(['purchase_unit_id']);
            $table->dropForeign(['sale_unit_id']);
            $table->dropColumn([
                'purchase_unit_id',
                'purchase_quantity', 
                'cost_per_sale_unit',
                'sale_unit_id',
                'available_sale_quantity',
                'min_selling_price',
                'max_selling_price',
                'conversion_factor'
            ]);
        });
    }
};
