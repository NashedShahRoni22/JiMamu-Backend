<?php
namespace App\Services\Rider;

use Illuminate\Support\Facades\Redis;

class LocationService{

    public function findNearbyRiders($orderRequest){
        return Redis::georadius('rider_locations', $orderRequest->pickup_longitude, $orderRequest->pickup_latitude, $orderRequest->pickup_radius, 'km');
    }
}
