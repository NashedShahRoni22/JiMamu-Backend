<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RiderLocationController extends Controller
{
    public function locationUpdate(Request $request){
        Redis::geoadd('rider_locations', 90.36, 23.76, 'rider:6');
        Redis::geoadd('rider_locations', 90.35, 23.755, 'rider:7');
        try {
           // $id =  auth()->id();
//            $id =  7;
//            Redis::geoadd('rider_locations', $request->latitude, $request->longitude, "rider:$id");
            return sendResponse(success: true, message: 'successfully updated locations.');
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'something went wrong.');
        }
    }
}
