<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RiderLocationController extends Controller
{
    public function locationUpdate(Request $request){
        try {
            $id =  auth()->id();
            Redis::geoadd('rider_locations', $request->latitude, $request->longitude, "rider:$id");
            return sendResponse(success: true, message: 'successfully updated locations.');
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'something went wrong.');
        }
    }
}
