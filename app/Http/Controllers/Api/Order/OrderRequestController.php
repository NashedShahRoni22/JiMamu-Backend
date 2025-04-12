<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\OrderRequest;
use App\Services\Rider\LocationService;
use Illuminate\Http\Request;

class OrderRequestController extends Controller
{
    public function __construct(public LocationService $locationService){

    }
    public function orderRequest(Request $request){

        try {
           $orderRequest = OrderRequest::create([
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
            $this->locationService->findNearbyRiders($orderRequest);
            return sendResponse(success: true, message: 'Successfully send order request');
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'something went wrong', data: null, status: 422,  error: $exception->getMessage());
        }

    }
}
