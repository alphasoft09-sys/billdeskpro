<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;

class HardwareShopSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Tools', 'description' => 'Hand tools and power tools'],
            ['name' => 'Hardware', 'description' => 'Nuts, bolts, screws, and fasteners'],
            ['name' => 'Electrical', 'description' => 'Electrical components and supplies'],
            ['name' => 'Plumbing', 'description' => 'Plumbing supplies and fixtures'],
            ['name' => 'Paint & Supplies', 'description' => 'Paints, brushes, and painting supplies']
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create suppliers
        $suppliers = [
            [
                'name' => 'ABC Hardware Supply',
                'contact_person' => 'John Smith',
                'email' => 'john@abchardware.com',
                'phone' => '+91-9876543210',
                'address' => '123 Industrial Area, Mumbai',
                'gst_number' => '27ABCDE1234F1Z5'
            ],
            [
                'name' => 'XYZ Tools & Equipment',
                'contact_person' => 'Sarah Johnson',
                'email' => 'sarah@xyztools.com',
                'phone' => '+91-9876543211',
                'address' => '456 Commercial Street, Delhi',
                'gst_number' => '07FGHIJ5678K9L2'
            ],
            [
                'name' => 'Electrical Components Ltd',
                'contact_person' => 'Mike Wilson',
                'email' => 'mike@electrical.com',
                'phone' => '+91-9876543212',
                'address' => '789 Tech Park, Bangalore',
                'gst_number' => '29MNOPQ9012R3S4'
            ]
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }

        // Create customers
        $customers = [
            [
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh@email.com',
                'phone' => '+91-9876543213',
                'address' => '101 Residential Area, Mumbai',
                'gst_number' => '27TUVWX3456Y7Z8'
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya@email.com',
                'phone' => '+91-9876543214',
                'address' => '202 Business District, Delhi',
                'gst_number' => '07ABCDE9012F3G4'
            ],
            [
                'name' => 'Amit Patel',
                'email' => 'amit@email.com',
                'phone' => '+91-9876543215',
                'address' => '303 Industrial Zone, Ahmedabad',
                'gst_number' => '24HIJKL5678M9N0'
            ]
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        // Create products
        $products = [
            // Tools
            [
                'name' => 'Hammer 500g',
                'sku' => 'HAM-500',
                'description' => 'Heavy duty claw hammer',
                'category_id' => 1,
                'purchase_price' => 150.00,
                'selling_price' => 200.00,
                'stock_quantity' => 25,
                'min_stock_level' => 5,
                'unit' => 'pcs'
            ],
            [
                'name' => 'Screwdriver Set',
                'sku' => 'SCW-SET-6',
                'description' => '6 piece screwdriver set',
                'category_id' => 1,
                'purchase_price' => 300.00,
                'selling_price' => 450.00,
                'stock_quantity' => 15,
                'min_stock_level' => 3,
                'unit' => 'set'
            ],
            [
                'name' => 'Drill Machine',
                'sku' => 'DRL-13MM',
                'description' => '13mm cordless drill machine',
                'category_id' => 1,
                'purchase_price' => 2500.00,
                'selling_price' => 3500.00,
                'stock_quantity' => 8,
                'min_stock_level' => 2,
                'unit' => 'pcs'
            ],
            // Hardware
            [
                'name' => 'M6 Bolts (Pack of 50)',
                'sku' => 'BOLT-M6-50',
                'description' => 'M6 x 20mm bolts',
                'category_id' => 2,
                'purchase_price' => 80.00,
                'selling_price' => 120.00,
                'stock_quantity' => 100,
                'min_stock_level' => 20,
                'unit' => 'pack'
            ],
            [
                'name' => 'Wood Screws (Pack of 100)',
                'sku' => 'SCRW-WD-100',
                'description' => '2 inch wood screws',
                'category_id' => 2,
                'purchase_price' => 120.00,
                'selling_price' => 180.00,
                'stock_quantity' => 75,
                'min_stock_level' => 15,
                'unit' => 'pack'
            ],
            // Electrical
            [
                'name' => 'LED Bulb 9W',
                'sku' => 'LED-9W',
                'description' => '9W LED bulb warm white',
                'category_id' => 3,
                'purchase_price' => 80.00,
                'selling_price' => 120.00,
                'stock_quantity' => 200,
                'min_stock_level' => 50,
                'unit' => 'pcs'
            ],
            [
                'name' => 'Electrical Wire 2.5mm',
                'sku' => 'WIRE-2.5MM',
                'description' => '2.5mm electrical wire per meter',
                'category_id' => 3,
                'purchase_price' => 25.00,
                'selling_price' => 40.00,
                'stock_quantity' => 500,
                'min_stock_level' => 100,
                'unit' => 'meter'
            ],
            // Plumbing
            [
                'name' => 'PVC Pipe 1/2 inch',
                'sku' => 'PVC-1/2',
                'description' => '1/2 inch PVC pipe per meter',
                'category_id' => 4,
                'purchase_price' => 30.00,
                'selling_price' => 50.00,
                'stock_quantity' => 300,
                'min_stock_level' => 50,
                'unit' => 'meter'
            ],
            [
                'name' => 'Teflon Tape',
                'sku' => 'TEFLON-TAPE',
                'description' => 'Plumber\'s tape roll',
                'category_id' => 4,
                'purchase_price' => 15.00,
                'selling_price' => 25.00,
                'stock_quantity' => 150,
                'min_stock_level' => 30,
                'unit' => 'roll'
            ],
            // Paint & Supplies
            [
                'name' => 'Paint Brush Set',
                'sku' => 'BRUSH-SET-5',
                'description' => '5 piece paint brush set',
                'category_id' => 5,
                'purchase_price' => 200.00,
                'selling_price' => 300.00,
                'stock_quantity' => 20,
                'min_stock_level' => 5,
                'unit' => 'set'
            ],
            [
                'name' => 'White Paint 1L',
                'sku' => 'PAINT-WHT-1L',
                'description' => '1 liter white emulsion paint',
                'category_id' => 5,
                'purchase_price' => 150.00,
                'selling_price' => 220.00,
                'stock_quantity' => 50,
                'min_stock_level' => 10,
                'unit' => 'liter'
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}