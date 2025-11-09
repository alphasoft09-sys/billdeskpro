<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Piece', 'symbol' => 'pcs', 'description' => 'Individual items'],
            ['name' => 'Liter', 'symbol' => 'L', 'description' => 'Volume measurement'],
            ['name' => 'Kilogram', 'symbol' => 'kg', 'description' => 'Weight measurement'],
            ['name' => 'Gram', 'symbol' => 'g', 'description' => 'Small weight measurement'],
            ['name' => 'Meter', 'symbol' => 'm', 'description' => 'Length measurement'],
            ['name' => 'Centimeter', 'symbol' => 'cm', 'description' => 'Small length measurement'],
            ['name' => 'Square Meter', 'symbol' => 'm²', 'description' => 'Area measurement'],
            ['name' => 'Cubic Meter', 'symbol' => 'm³', 'description' => 'Volume measurement'],
            ['name' => 'Box', 'symbol' => 'box', 'description' => 'Packaged items'],
            ['name' => 'Pack', 'symbol' => 'pack', 'description' => 'Packaged items'],
            ['name' => 'Set', 'symbol' => 'set', 'description' => 'Group of items'],
            ['name' => 'Pair', 'symbol' => 'pair', 'description' => 'Two items together'],
            ['name' => 'Dozen', 'symbol' => 'dozen', 'description' => '12 items'],
            ['name' => 'Hundred', 'symbol' => '100', 'description' => '100 items'],
            ['name' => 'Thousand', 'symbol' => '1000', 'description' => '1000 items'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}