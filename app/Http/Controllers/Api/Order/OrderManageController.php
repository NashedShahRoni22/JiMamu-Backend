<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManageController extends Controller
{
    // order woner and accepted rider can cancel accepted bid
    public function acceptedBidCancel($order_id){
        $order = Order::where('order_unique_id', $order_id)
            ->where('customer_id', auth()->id())
            ->orWhere('rider_id', auth()->id())
            ->firstOrFail();
        try {
           $order->update(['status' => Order::$ORDER_STATUS['cancelled'], 'rider_id' => null]);
            return sendResponse(true, 'Order Bid cancelled successfully.');
        }catch (\Exception $e){
            return sendResponse(false, 'Something went wrong. Please try again.');
        }
    }
}
