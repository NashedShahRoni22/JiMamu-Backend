<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\DeviceToken;
use App\Models\Order;
use App\Models\UserRider;
use App\Services\Notifications\FcmService;
use Illuminate\Http\Request;
use phpseclib3\Crypt\RSA\PublicKey;

class BidsController extends Controller
{
    public function __construct(Public FcmService $fcmService)
    {

    }
    public function newBidList()
    {
        $orderBids = Order::get();
        return sendResponse(true, 'Successfully get data.', $orderBids);

    }
    // show new one bids
    public function newBids($order_id){
       $order =  Order::where('order_unique_id', $order_id)->with('orderAttempt')->firstOrFail();
       $data  = [
           'order_id' => $order->order_unique_id,
           'pickup_latitude' => $order->pickup_latitude,
           'pickup_longitude' => $order->pickup_longitude,
           'drop_latitude' => $order->drop_latitude,
           'drop_longitude' => $order->drop_longitude,
           'date' => $order->created_at->format('Y-m-d'),
           'fare' => $order->orderAttempt->fare,
       ];
       return sendResponse(true, 'Successfully get data.', $data);
    }
    public function applyBids(Request $request, $order_id){
        $order =  Order::where('order_unique_id', $order_id)->with('orderAttempt')->firstOrFail();
        // user can not apply their own orders
        if ($order->customer_id === auth()->id()){
            return sendResponse(false, 'You cannot apply your bids.');
        }

//        $maxBidPrice = $order->orderAttempt?->fare + $order->orderAttempt?->fare * 30 / 100;
//        $minBidPrice = $order->orderAttempt?->fare - $order->orderAttempt?->fare * 20 / 100;
//        // check maximum
//        if($maxBidPrice < $request->bid_amount){
//            return sendResponse(false, 'Your bid amount to much high', ['max_bid' => $maxBidPrice], 422);
//        }
//        if($minBidPrice > $request->bid_amount){
//            return sendResponse(false, 'Your bid amount to much low.', ['min_bid' => $minBidPrice], 422);
//        }

        $userRider = UserRider::where('user_id', auth()->id())->first();

        // check exist under a order
      if (
            !auth()->user()->hasRole('rider') ||
            ($userRider?->review_status !== UserRider::$REVIEW_STATUS_NAME['approved'])
        ) {
            return sendResponse(false, 'You are not eligible for rider.', null, 404);
        }

        $findBid = Bid::where('order_id', $order->id)->where('user_id', auth()->id())->first();
        if($findBid){
            return sendResponse(false, 'You are already applied.', null, 409);
        }
        try {
            Bid::create([
                'order_id' => $order->id,
                'order_attempt_id' => $order?->orderAttempt?->id,
                'user_id' => auth()->id(),
                'bid_amount' => $request->bid_amount
            ]);

            // Notifications
             $token = DeviceToken::were('user_id', $order->customer_id)->value('device_token');
                app(FcmService::class)->sendToDevice(
                    $token,
                    'New Bid Received 🏍️',
                    'A rider has placed a bid on your order. Tap to review!',
                    'new_bid_received',
                    ['order_id' => '123']
                );
            return sendResponse(true, 'Your bid has been successful.', null, 201);
        }catch (\Exception $exception){
            return sendResponse(false, 'Something went wrong!', null,422, $exception->getMessage());
        }
    }
    public function myOrderAppliedBids($order_type = 'national')
    {
        try {
            $orders = Order::where('order_type', Order::$ORDER_TYPE[$order_type])
                ->whereHas('orderAttempts.bids', function ($q) {
                    $q->where('user_id', auth()->id());
                })
                ->with('package:id,name')
                ->with(['orderAttempt' => function ($query) {
                    $query->with(['bid' => function ($q) {
                        $q->where('user_id', auth()->id());
                    }]);
                }])
                ->latest()
                ->get();

            return sendResponse(true, 'Successfully get data.', $orders);
        } catch (\Exception $e) {
            return sendResponse(false, 'Something went wrong!', null, 422, $e->getMessage());
        }
    }


}
