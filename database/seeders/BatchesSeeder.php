<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\Product;
use App\Models\Supplier;

class BatchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some products and suppliers for creating batches
        $products = Product::take(5)->get();
        $suppliers = Supplier::take(3)->get();

        if ($products->isEmpty() || $suppliers->isEmpty()) {
            $this->command->info('No products or suppliers found. Please run HardwareShopSeeder first.');
            return;
        }

        $batchData = [
            [
                'product_id' => $products[0]->id,
                'supplier_id' => $suppliers[0]->id,
                'batch_number' => 'BATCH-001-2024',
                'supplier_batch_number' => 'SUP-001',
                'purchase_price' => 50.00,
                'selling_price' => 75.00,
                'quantity_received' => 100,
                'quantity_remaining' => 100,
                'production_date' => now()->subDays(30),
                'expiry_date' => now()->addDays(365),
                'received_date' => now()->subDays(25),
                'notes' => 'First batch of screws',
            ],
            [
                'product_id' => $products[0]->id,
                'supplier_id' => $suppliers[1]->id,
                'batch_number' => 'BATCH-002-2024',
                'supplier_batch_number' => 'SUP-002',
                'purchase_price' => 55.00,
                'selling_price' => 80.00,
                'quantity_received' => 150,
                'quantity_remaining' => 120,
                'production_date' => now()->subDays(20),
                'expiry_date' => now()->addDays(365),
                'received_date' => now()->subDays(15),
                'notes' => 'Second batch with higher quality',
            ],
            [
                'product_id' => $products[1]->id,
                'supplier_id' => $suppliers[0]->id,
                'batch_number' => 'BATCH-003-2024',
                'supplier_batch_number' => 'SUP-003',
                'purchase_price' => 25.00,
                'selling_price' => 40.00,
                'quantity_received' => 200,
                'quantity_remaining' => 180,
                'production_date' => now()->subDays(10),
                'expiry_date' => now()->addDays(180),
                'received_date' => now()->subDays(5),
                'notes' => 'Nails batch',
            ],
            [
                'product_id' => $products[2]->id,
                'supplier_id' => $suppliers[2]->id,
                'batch_number' => 'BATCH-004-2024',
                'supplier_batch_number' => 'SUP-004',
                'purchase_price' => 100.00,
                'selling_price' => 150.00,
                'quantity_received' => 50,
                'quantity_remaining' => 30,
                'production_date' => now()->subDays(5),
                'expiry_date' => now()->addDays(730),
                'received_date' => now()->subDays(2),
                'notes' => 'Premium tools batch',
            ],
            [
                'product_id' => $products[3]->id,
                'supplier_id' => $suppliers[1]->id,
                'batch_number' => 'BATCH-005-2024',
                'supplier_batch_number' => 'SUP-005',
                'purchase_price' => 15.00,
                'selling_price' => 25.00,
                'quantity_received' => 300,
                'quantity_remaining' => 250,
                'production_date' => now()->subDays(3),
                'expiry_date' => now()->addDays(90),
                'received_date' => now()->subDays(1),
                'notes' => 'Fasteners batch',
            ],
        ];

        foreach ($batchData as $data) {
            Batch::firstOrCreate(
                ['batch_number' => $data['batch_number']],
                $data
            );
        }

        $this->command->info('Batches seeded successfully!');
    }
}