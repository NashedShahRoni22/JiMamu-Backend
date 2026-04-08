<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\DeviceToken;
use App\Models\Order;
use App\Models\PricingRate;
use App\Services\Notifications\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderManageController extends Controller
{
    public function __construct(public FcmService $fcmService){}
    // order woner and accepted rider can cancel accepted bid
public function acceptedBidCancel($order_id, $rider_id){

    $order = Order::where('order_unique_id', $order_id)
        ->with('bids')
        ->firstOrFail();

    if(!$order){
        return sendResponse(false, 'Order not found.', 404);
    }

    $bid = $order->bids->where('status', Bid::$STATUS['accepted'])->first();

    if ($bid) {
        return sendResponse(false, 'Bid already accepted, You can not cancel this bid.');
    }

    try {

        $bid = $order->bids->firstWhere('user_id', $rider_id);

        // ✅ prevent crash if bid not found
        if (!$bid) {
            return sendResponse(false, 'Bid not found.', 404);
        }

        $bid->forceDelete();

        // 🔔 Get token
        $token = DeviceToken::where('user_id', $rider_id)->value('device_token');

        // ✅ Send notification safely
        if ($token) {
            try {
                app(FcmService::class)->sendToDevice(
                    $token,
                    'Order Cancelled ❌',
                    'Your applied bid has been cancelled.',
                    'order_cancelled',
                    [
                        'order_id' => $order->order_unique_id,
                    ]
                );

            } catch (\Exception $fcmException) {

                $errorMessage = $fcmException->getMessage();

                // 🔥 Remove invalid token
                if (
                    str_contains($errorMessage, 'Requested entity was not found') ||
                    str_contains($errorMessage, 'registration-token-not-registered') ||
                    str_contains($errorMessage, 'invalid-registration-token') ||
                    str_contains($errorMessage, 'invalid-argument')
                ) {
                    DeviceToken::where('device_token', $token)->delete();
                }

                \Log::error('FCM Error: '.$errorMessage);
            }
        }

        return sendResponse(true, 'Order Bid cancelled successfully.');

    } catch (\Exception $e){

        \Log::error('Cancel Bid Error: '.$e->getMessage());

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
