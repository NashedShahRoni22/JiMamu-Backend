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
            ['min_distance_km' => 0, 'max_distance_km' => 50,   'base_fare' => 10],
            ['min_distance_km' => 51, 'max_distance_km' => 100, 'base_fare' => 20],
            ['min_distance_km' => 101, 'max_distance_km' => 500,'base_fare' => 50],
            ['min_distance_km' => 501, 'max_distance_km' => 1000,'base_fare' => 100],
            ['min_distance_km' => 1001, 'max_distance_km' => null,'base_fare' => 200],
        ]);
    }
}
