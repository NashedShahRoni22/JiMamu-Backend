<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            [
                'name' => 'Documents',
                'sort_description' => 'No Passport or bank cheques',
                'icon' => null,
                'status' => 1,
            ],
            [
                'name' => 'Homemade food',
                'sort_description' => 'Home cooked food',
                'icon' => null,
                'status' => 1,
            ],
            [
                'name' => 'Clothes',
                'sort_description' => 'Fold neatly and pack securely',
                'icon' => null,
                'status' => 1,
            ],
            [
                'name' => 'Gifts',
                'sort_description' => 'Flowers, cards, chocolates, and others',
                'icon' => null,
                'status' => 1,
            ],
            [
                'name' => 'Cosmetics',
                'sort_description' => 'Makeup, skincare, or hygiene products. Ensure liquids are sealed',
                'icon' => null,
                'status' => 1,
            ],
            [
                'name' => 'Medicine',
                'sort_description' => 'Prescription or over-the-counter medicines, securely packed',
                'icon' => null,
                'status' => 1,
            ],
            [
                'name' => 'Accessories',
                'sort_description' => 'Watches, jewellery, bags, shoes etc, comment if fragile',
                'icon' => null,
                'status' => 1,
            ],
            [
                'name' => 'Perishable',
                'sort_description' => 'Fruits, vegetables, fishes, frozen foods etc',
                'icon' => null,
                'status' => 1,
            ],
            [
                'name' => 'Electronics',
                'sort_description' => 'Bubble-wrapped, comment if fragile',
                'icon' => null,
                'status' => 1,
            ],
            [
                'name' => 'Other Items',
                'sort_description' => 'Art supplies, toys, stationery, small tools, or household items',
                'icon' => null,
                'status' => 1,
            ],
        ];

        DB::table('packages')->upsert(
            $packages,
            ['name'], // unique key
            ['sort_description', 'icon', 'status']
        );
    }
}
