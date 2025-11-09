<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\UnitConversion;
use App\Models\Batch;
use App\Models\Supplier;

class CompleteProductExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating complete product example...');

        // Step 1: Create Category
        $category = Category::firstOrCreate(
            ['name' => 'Fasteners'],
            ['description' => 'Screws, bolts, nuts, and other fastening hardware']
        );
        $this->command->info("✅ Category created: {$category->name}");

        // Step 2: Create Units
        $pieceUnit = Unit::firstOrCreate(
            ['name' => 'Piece'],
            ['symbol' => 'pcs', 'description' => 'Individual items']
        );
        $boxUnit = Unit::firstOrCreate(
            ['name' => 'Box'],
            ['symbol' => 'box', 'description' => 'Packaged items']
        );
        $this->command->info("✅ Units created: {$pieceUnit->name}, {$boxUnit->name}");

        // Step 3: Create Product
        $product = Product::create([
            'name' => 'Steel Bolts M8',
            'sku' => 'BOLT-M8-001',
            'description' => 'High-quality steel bolts M8 x 20mm',
            'category_id' => $category->id,
            'unit_id' => $pieceUnit->id,
            'purchase_price' => 12.00,
            'selling_price' => 18.00,
            'stock_quantity' => 0, // Will be updated via batches
            'min_stock_level' => 50,
            'pricing_method' => 'percentage',
            'markup_percentage' => 50.00,
            'auto_calculate_selling_price' => true
        ]);
        $this->command->info("✅ Product created: {$product->name}");

        // Step 4: Create Unit Conversion
        $conversion = UnitConversion::create([
            'product_id' => $product->id,
            'from_unit_id' => $boxUnit->id,
            'to_unit_id' => $pieceUnit->id,
            'conversion_factor' => 100.0000, // 1 box = 100 pieces
            'is_active' => true
        ]);
        $this->command->info("✅ Unit conversion created: 1 {$boxUnit->name} = 100 {$pieceUnit->name}");

        // Step 5: Create Batches
        $supplier = Supplier::first();
        if (!$supplier) {
            $supplier = Supplier::create([
                'name' => 'ABC Hardware Supplier',
                'contact_person' => 'John Doe',
                'email' => 'john@abchardware.com',
                'phone' => '+91-9876543210',
                'address' => '123 Hardware Street, Mumbai',
                'gst_number' => '27ABCDE1234F1Z5'
            ]);
        }

        // Batch 1
        $batch1 = Batch::create([
            'product_id' => $product->id,
            'supplier_id' => $supplier->id,
            'batch_number' => 'BOLT-M8-001-2024',
            'supplier_batch_number' => 'SUP-BOLT-001',
            'purchase_price' => 12.00,
            'selling_price' => 18.00,
            'quantity_received' => 1000,
            'quantity_remaining' => 1000,
            'production_date' => now()->subDays(5),
            'expiry_date' => now()->addDays(365),
            'received_date' => now()->subDays(2),
            'notes' => 'First batch of steel bolts M8',
            'is_active' => true
        ]);

        // Batch 2 (different price)
        $batch2 = Batch::create([
            'product_id' => $product->id,
            'supplier_id' => $supplier->id,
            'batch_number' => 'BOLT-M8-002-2024',
            'supplier_batch_number' => 'SUP-BOLT-002',
            'purchase_price' => 11.50,
            'selling_price' => 17.25,
            'quantity_received' => 500,
            'quantity_remaining' => 500,
            'production_date' => now()->subDays(3),
            'expiry_date' => now()->addDays(365),
            'received_date' => now()->subDays(1),
            'notes' => 'Second batch with better pricing',
            'is_active' => true
        ]);

        $this->command->info("✅ Batches created:");
        $this->command->info("   - {$batch1->batch_number}: ₹{$batch1->purchase_price} → ₹{$batch1->selling_price} (1000 pieces)");
        $this->command->info("   - {$batch2->batch_number}: ₹{$batch2->purchase_price} → ₹{$batch2->selling_price} (500 pieces)");

        // Summary
        $this->command->info("\n🎉 COMPLETE PRODUCT SETUP SUMMARY:");
        $this->command->info("📁 Category: {$category->name}");
        $this->command->info("📦 Product: {$product->name} (SKU: {$product->sku})");
        $this->command->info("📏 Unit: {$pieceUnit->name} ({$pieceUnit->symbol})");
        $this->command->info("🔄 Conversion: 1 {$boxUnit->name} = 100 {$pieceUnit->name}");
        $this->command->info("📋 Batches: 2 batches with different prices");
        $this->command->info("💰 Price Range: ₹11.50 - ₹18.00");
        $this->command->info("📊 Total Stock: 1500 pieces");
        $this->command->info("🏪 Supplier: {$supplier->name}");

        $this->command->info("\n✅ Complete product example created successfully!");
    }
}