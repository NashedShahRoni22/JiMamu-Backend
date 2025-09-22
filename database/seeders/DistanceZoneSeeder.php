<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistanceZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('distance_zones')->insert([
            ['min_distance_km' => 0, 'max_distance_km' => 50,   'platform_charge' =>10 ],
            ['min_distance_km' => 51, 'max_distance_km' => 100, 'platform_charge' =>12 ],
            ['min_distance_km' => 101, 'max_distance_km' => 500,'platform_charge' =>15 ],
            ['min_distance_km' => 501, 'max_distance_km' => 1000,'platform_charge' =>16 ],
            ['min_distance_km' => 1001, 'max_distance_km' => null,'platform_charge' =>20 ],
        ]);
    }
}
