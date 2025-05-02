<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Order;
use Illuminate\Http\Request;

class BidsController extends Controller
{
    // show new one bids
    public function newBids($order_id){
       $order =  Order::where('order_unique_id', $order_id)->firstOrFail();
       $data  = [
           'order_id' => $order->order_unique_id,
           'pickup_latitude' => $order->pickup_latitude,
           'pickup_longitude' => $order->pickup_longitude,
           'drop_latitude' => $order->drop_latitude,
           'drop_longitude' => $order->drop_longitude,
           'date' => $order->created_at->format('Y-m-d'),
           'fare' => $order->fare,
       ];
       return sendResponse(true, 'Successfully get data.', $data);
    }
    public function applyBids(Request $request, $order_id){
        $order =  Order::where('order_unique_id', $order_id)->with('orderAttempt')->firstOrFail();
        $maxBidPrice = $order->orderAttempt?->fare + $order->orderAttempt?->fare * 30 / 100;
        $minBidPrice = $order->orderAttempt?->fare - $order->orderAttempt?->fare * 20 / 100;
        // check maximum
        if($maxBidPrice < $request->bid_amount){
            return sendResponse(false, 'Your bid amount to much high', ['max_bid' => $maxBidPrice], 422);
        }
        if($minBidPrice > $request->bid_amount){
            return sendResponse(false, 'Your bid amount to much low.', ['min_bid' => $minBidPrice], 422);
        }
        // check exist under a order
        $findBid = Bid::where('order_id', $order->id)->where('user_id', auth()->id())->first();
        if($findBid){
            return sendResponse(false, 'You are already applied.', null, 409);
        }
        try {
            Bid::create([
                'order_id' => $order->id,
                'order_attempt_id' => $order->orderAttempt->order_attempt_id,
                'user_id' => auth()->id(),
                'bid_amount' => $request->bid_amount
            ]);
            return sendResponse(true, 'Your bid has been successful.', null, 201);
        }catch (\Exception $exception){
            return sendResponse(false, 'Something went wrong!', null,422, $exception->getMessage());
        }
    }
}
