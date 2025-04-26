<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\OrderRequest;
use App\Services\Rider\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class OrderRequestController extends Controller
{
    public function __construct(public LocationService $locationService){

    }
    public function orderRequest(Request $request){
//        $rider = auth()->id();
//       return Redis::geopos('rider_locations', "rider:$rider");
//        $lat = 23.7611;
//        $lng = 90.3623;
//
//        $response = Http::withHeaders([
//            'User-Agent' => 'YourAppName/1.0 (your@email.com)'
//        ])->get("https://nominatim.openstreetmap.org/reverse", [
//            'format' => 'json',
//            'lat' => $lat,
//            'lon' => $lng,
//            'zoom' => 18,
//            'addressdetails' => 1,
//        ]);
//
//        $data = $response->json();
//       return $address = $data['display_name'] ?? null;

        // Step 1: Get all members from the geo set
        $members = Redis::zrange('rider_locations', 0, -1);

        $locations = [];

        // Step 2: Loop through and get position of each rider
        foreach ($members as $member) {
            $position = Redis::geopos('rider_locations', $member);

            if (!empty($position[0])) {
                $locations[] = [
                    'rider_id' => str_replace('rider:', '', $member),
                    'longitude' => $position[0][0],
                    'latitude' => $position[0][1],
                ];
            }
        }
        $nearby = Redis::georadius('rider_locations', 90.3582, 23.7564, 1, 'km');
        $riderIds = collect($nearby)->map(function ($key) {
            return str_replace('rider:', '', $key);
        });

        return $riderIds;
        try {
           $orderRequest = order::create([
                'user_id' => auth()->id(),
                'package_id' => $request->package_id,
                'pickup_latitude' => $request->pickup_latitude,
                'pickup_longitude' => $request->pickup_longitude,
                'drop_latitude' => $request->drop_latitude,
                'drop_longitude' => $request->drop_longitude,
                'weight' => $request->weight,
                'price' => $request->price,
                'pickup_radius' => $request->pickup_radius
            ]);
            $nearbyRiders = Redis::command('georadius', [
                'rider_locations',
                $request->pickup_longitude,
                $request->pickup_latitude,
                $request->pickup_radius,
                'km',
            ]);
            return $nearbyRiders;
           return $this->locationService->findNearbyRiders($orderRequest);
            return sendResponse(success: true, message: 'Successfully send order request');
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'something went wrong', data: null, status: 422,  error: $exception->getMessage());
        }

    }
}
