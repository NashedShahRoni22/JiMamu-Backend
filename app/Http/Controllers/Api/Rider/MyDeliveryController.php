<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use App\Http\Resources\MyOrderDetailsResource;
use App\Models\Order;
use Illuminate\Http\Request;

class MyDeliveryController extends Controller
{
    public function myOngoingOrder()
    {
        if(!auth()->user()->hasRole('rider')){
            return sendResponse(false, 'Your are not rider', null, 403);
        }
        $order = Order::whereIn('status', [Order::$ORDER_STATUS['confirmed'], Order::$ORDER_STATUS['picked'], Order::$ORDER_STATUS['shipping']])
            ->where('rider_id', auth()->id())
            ->with('orderAttempts.bids', 'orderAttempts.bid')->get();
        try {

            $data = MyOrderDetailsResource::collection($order);
            return sendResponse(success: true, message: 'Successfully Get Data', data: $data);
        } catch (\Exception $exception) {
            return sendResponse(false, message: 'something went wrong', data: null, status: 422);
        }
    }
    public function myOngoingOrderDetails($orderUniqueId){
        if(!auth()->user()->hasRole('rider')){
            return sendResponse(false, 'Your are not rider', null, 403);
        }
        $order = Order::where('order_unique_id', $orderUniqueId)
            ->whereIn('status', [Order::$ORDER_STATUS['confirmed'], Order::$ORDER_STATUS['picked'], Order::$ORDER_STATUS['shipping']])
            ->where('rider_id', auth()->id())
            //->where('created_at', '>=', Carbon::now()->subMinutes(5))
            ->with('orderAttempts.bids', 'orderAttempts.bid')->get();
        if(!$order){
            return sendResponse(false, 'Order Not Found', data: null, status: 404);
        }
        try {
            $data =  MyOrderDetailsResource::collection($order);
            return sendResponse(success: true, message: 'Successfully get data', data: $data);
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'Something went wrong', data: null, status: 422);
        }
    }
    public function myNewOrderRequest()
    {
        if(!auth()->user()->hasRole('rider')){
            return sendResponse(false, 'Your are not rider', null, 403);
        }
        try {
            $order = Order::where('status', Order::$ORDER_STATUS['pending'])
                //->whereNot('rider_id', auth()->id())
                ->whereNot('customer_id', auth()->id())
                ->with('orderAttempts.bids', 'orderAttempts.bid')->get();
            $data = MyOrderDetailsResource::collection($order);
            return sendResponse(success: true, message: 'Successfully Get Data', data: $data);
        } catch (\Exception $exception) {
            return sendResponse(false, message: 'something went wrong', data: null, status: 422);
        }
    }
    public function myCompletedOrderList()
    {
        if(!auth()->user()->hasRole('rider')){
            return sendResponse(false, 'Your are not rider', null, 403);
        }
        try {
            $order = Order::where('rider_id', auth()->id())
                ->where('status', Order::$ORDER_STATUS['delivered'])
                ->with('orderAttempts.bids', 'orderAttempts.bid')->get();
            $data = MyOrderDetailsResource::collection($order);
            return sendResponse(success: true, message: 'Successfully Get Data', data: $data);
        } catch (\Exception $exception) {
            return sendResponse(false, message: 'something went wrong', data: null, status: 422);
        }
    }

}
