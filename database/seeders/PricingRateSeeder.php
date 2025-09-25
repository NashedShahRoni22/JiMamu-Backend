<?php

namespace Database\Seeders;

use App\Models\PricingRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PricingRate::create([
            'base_fare' => '10',
            'platform_charge' => '5',
            'type' => 1,
        ]);
        PricingRate::create([
            'base_fare' => '25',
            'platform_charge' => '7',
            'type' => 2,
        ]);
    }
}
