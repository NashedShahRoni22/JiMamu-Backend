<?php

namespace App\Services\Order;

use App\Models\DistanceZone;
use App\Models\WeightRule;

class FareCalculatorService
{
    public function calculateFare(float $distance, float $weight): ?float
    {
        // 1. Get Distance Zone
        $zone = DistanceZone::where('min_distance_km', '<=', $distance)
            ->where(function ($query) use ($distance) {
                $query->where('max_distance_km', '>=', $distance)
                    ->orWhereNull('max_distance_km');
            })
            ->first();

        if (! $zone) {
            return null; // no zone found
        }

        // 2. Get Weight Rule
        $rule = WeightRule::where('min_weight_kg', '<=', $weight)
            ->where(function ($query) use ($weight) {
                $query->where('max_weight_kg', '>=', $weight)
                    ->orWhereNull('max_weight_kg');
            })
            ->first();

        // 3. Base Fare (distance-based)
        $fare = $zone->base_fare + ($distance * $zone->per_km_rate);

        // 4. Add Weight Pricing
        if ($rule && ! $rule->is_base_price) {
            $fare += $weight * $rule->extra_cost_per_kg;
        }

        return $fare;
    }
}
