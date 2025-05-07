<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use App\Http\Resources\MyOrderDetailsResource;
use App\Models\Order;
use Illuminate\Http\Request;

class MyDeliveryController extends Controller
{
    public function myCompletedOrderList()
    {
        try {
            $order = Order::where('customer_id', auth()->id())
                ->where('status', Order::$ORDER_STATUS['delivered'])
                ->with('orderAttempts.bids', 'orderAttempts.bid')->get();
            $data = MyOrderDetailsResource::collection($order);
            return sendResponse(success: true, message: 'Successfully Get Data', data: $data);
        } catch (\Exception $exception) {
            return sendResponse(false, message: 'something went wrong', data: null, status: 422);
        }
    }
}
