<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeightRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('weight_rules')->insert([
            ['min_weight_kg' => 0,   'max_weight_kg' => 1,   'cost_per_kg' => 0,  'is_base_price' => true],
            ['min_weight_kg' => 1,   'max_weight_kg' => 5,   'cost_per_kg' => 10, 'is_base_price' => false],
            ['min_weight_kg' => 5,   'max_weight_kg' => 10,  'cost_per_kg' => 8,  'is_base_price' => false],
            ['min_weight_kg' => 10,  'max_weight_kg' => null,'cost_per_kg' => 6,  'is_base_price' => false],
        ]);
    }
}
