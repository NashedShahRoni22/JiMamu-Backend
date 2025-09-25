<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PricingRate;
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
    public function orderOverview()
    {
        try {
            $stats = Order::selectRaw("
            SUM(CASE WHEN customer_id = ? AND status = ? THEN 1 ELSE 0 END) as totalCompletedMyOrders,
            SUM(CASE WHEN rider_id = ? AND status = ? THEN 1 ELSE 0 END) as totalCompletedMyDeliveries
        ", [
                auth()->id(),
                Order::$ORDER_STATUS['delivered'],
                auth()->id(),
                Order::$ORDER_STATUS['delivered']
            ])
                ->first();

            return sendResponse(true, 'Order Bid cancelled successfully.', $stats);

        }catch (\Exception $e){
            return sendResponse(false, 'Something went wrong. Please try again.');
        }

    }
    public function orderPricingRate(){
        $pricingData = PricingRate::whereIn('type', [1, 2])->get()->keyBy('type');

        $data = [
            'national' => $pricingData[1],
            'international' => $pricingData[2],
        ];
        return sendResponse(true, 'Pricing Rate', $data);
    }

}
