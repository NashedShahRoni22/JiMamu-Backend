<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Order;
use App\Models\PricingRate;
use Illuminate\Http\Request;

class OrderManageController extends Controller
{
    // order woner and accepted rider can cancel accepted bid
    public function acceptedBidCancel($order_id, $rider_id){
         $order = Order::where('order_unique_id', $order_id)
            ->with('bids')
            ->firstOrFail();


        $bid = $order->bids->where('status', Bid::$STATUS['accepted'])->first();
        if ($bid) {
            return sendResponse(false, 'Bid already accepted, You can not cancel this bid.');
        }
        try {
            $bid = $order->bids->firstWhere('user_id', $rider_id);
            $bid->forceDelete();
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

            return sendResponse(true, 'Order overview data get successfully.', $stats);

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
        return sendResponse(true, 'Pricing Rate data get successfully', $data);
    }

}
