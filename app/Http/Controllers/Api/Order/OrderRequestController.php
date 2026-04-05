<?php

namespace App\Http\Controllers\Api\Order;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Resources\MyOrderDetailsResource;
use App\Http\Resources\MyOrderListResource;
use App\Http\Resources\PackageResource;
use App\Mail\OtpMail;
use App\Models\Bid;
use App\Models\DeviceToken;
use App\Models\DistanceZone;
use App\Models\Order;
use App\Models\OrderAttempt;
use App\Models\OrderDestination;
use App\Models\OtpVerify;
use App\Models\Package;
use App\Models\PricingRate;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Models\WeightRule;
use App\Services\Payment\StripePaymentService;
use App\Services\Rider\LocationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use App\Services\Notifications\FcmService;

class OrderRequestController extends Controller
{
    public function __construct(public LocationService $locationService, public StripePaymentService $stripePaymentService, public FcmService $fcmService)
        {

    }
    public function orderRequest(Request $request){
         $rider = auth()->id();

//       return Redis::geopos('rider_locations', "rider:$rider");
//        $lat = 23.7611;
//        $lng = 90.3623;
//
//        $response = Http::withHeaders([
//            'User-Agent' => 'YourAppName/1.0 (your@email.com)'
//        ])->get("https://nominatim.openstreetmap.org/reverse", [
//            'format' => 'json',
//            'lat' => $lat,
//            'lon' => $lng,
//            'zoom' => 18,
//            'addressdetails' => 1,
//        ]);
//
//        $data = $response->json();
//       return $address = $data['display_name'] ?? null;

        // Step 1: Get all members from the geo set
//        $members = Redis::zrange('rider_locations', 0, -1);
//
//        $locations = [];
//
//        // Step 2: Loop through and get position of each rider
//        foreach ($members as $member) {
//            $position = Redis::geopos('rider_locations', $member);
//
//            if (!empty($position[0])) {
//                $locations[] = [
//                    'rider_id' => str_replace('rider:', '', $member),
//                    'longitude' => $position[0][0],
//                    'latitude' => $position[0][1],
//                ];
//            }
//        }
//        $nearby = Redis::georadius('rider_locations', 90.3582, 23.7564, 1, 'km');
//        $riderIds = collect($nearby)->map(function ($key) {
//            return str_replace('rider:', '', $key);
//        });
//
//        return $riderIds;
        $senderName = $request->input('sender_information.name');
//        Order::where('user_id', auth()->id())
//            ->where('package_id', $request->package_id)
//            ->where('pickup_latitude', $request->pickup_latitude)
//            ->where('pickup_longitude', $request->pickup_longitude)
//            ->where('drop_latitude', $request->drop_latitude)
//            ->where('drop_longitude', $request->drop_longitude)
//            ->first();
      // return $request->order_type == 'national' ? Order::$ORDER_TYPE['national'] : Order::$ORDER_TYPE['international'];
        try {
            DB::transaction(function () use ($request) {
                $orderRequest = Order::create([
                    'customer_id' => auth()->id(),
                    'order_unique_id' => rand(000000, 999999),
                    'package_id' => $request->package_id,
                    'order_type' => $request->order_type == 'national' ? Order::$ORDER_TYPE['national'] : Order::$ORDER_TYPE['international'],
                    'pickup_latitude' => $request->pickup_latitude,
                    'pickup_longitude' => $request->pickup_longitude,
                    'drop_latitude' => $request->drop_latitude,
                    'drop_longitude' => $request->drop_longitude,
                    'weight' => $request->weight,
                    'weight_type' => Order::$WEIGHT_TYPE[$request->weight_type],
                ]);
                $pricingRate = PricingRate::where('type', PricingRate::$STATUS[$request->order_type])->first();
                // cutting system base fare and platform change, rider will show only need fare
                $netFare = abs((float)($pricingRate->base_fare + $pricingRate->platform_charge) + (float)$request->total_fare);
                OrderAttempt::create([
                    'order_id' => $orderRequest->id,
                    'fare' =>$netFare,
                    'parcel_estimate_price' => $request->parcel_estimate_price,
                    'order_tracking_number' => rand(000000, 999999),
                ]);
                // when order request will have international
//               if($request->order_type == 'international' && $request->has('order_destination')){
//                   OrderDestination::create([
//                       'order_id' => $orderRequest->id,
//                       'country' => $request->input('order_destination.country'),
//                       'state' => $request->input('order_destination.state'),
//                       'city' =>  $request->input('order_destination.city'),
//                       'area' => $request->input('order_destination.area'),
//                       'address' => $request->input('order_destination.address'),
//
//                   ]);
//               }
                // customer sender informations
                if ($request->has('sender_information')) {
                    $senderName = $request->input('sender_information.name');
                    $senderPhone = $request->input('sender_information.phone_number');
                    $senderRemarks = $request->input('sender_information.remarks');

                    // Check if matching user exists
//                    $userExists = auth()->user()->where('name', $senderName)
//                        ->where('phone_number', $senderPhone)
//                        ->first();

                    // If no matching user, then store as sender information
                 //   if (!$userExists) {
                        $orderRequest->senderInformation()->create([
                            'sender_name' => $senderName,
                            'sender_phone' => $senderPhone,
                            'remarks' => $senderRemarks,
                        ]);
                   // }
                }
                // Store Receiver Info
                if ($request->has('receiver_information')) {
                    $orderRequest->receiverInformation()->create([
                        'receiver_name' => $request->input('receiver_information.name'),
                        'receiver_phone' => $request->input('receiver_information.phone_number'),
                        'remarks' => $request->input('receiver_information.remarks'),
                    ]);
                }

                // radius db store locations data
//                $nearbyRiders = Redis::command('georadius', [
//                    'rider_locations',
//                    $request->pickup_longitude,
//                    $request->pickup_latitude,
//                    $request->pickup_radius,
//                    'km',
//                ]);
                // return $nearbyRiders;
            //  $findNearByRiders =  $this->locationService->findNearbyRiders($orderRequest);

            //$tokens = "d87Ch6oBQbKXHnlinblYUS:APA91bFd46DoLNEpFBAHEazfwiufnM6yxTNaZzRc0aCfgGPckLCgeCq7Uo8gS1sx2IsiSeSEkFxQREXYYhqM1mOrhndaNXDK9lxYghOGl3QcVNMdhqmTF_I";

            // Get rider tokens
            $ridersId = User::role('rider')->pluck('id');
            $tokens = DeviceToken::whereIn('user_id', $ridersId)
                ->pluck('device_token')
                ->toArray();  // ← must be array

            // Send
            app(FcmService::class)->sendToMultiple(
                $tokens,
                'New Order Created 🛒',
                'A new order has been created. Please check the app for details.',
                'new_order_created',   // type
                ['order_id' => $orderRequest?->order_unique_id]  // extra data
            );
            });

            return sendResponse(success: true, message: 'Successfully send order request');
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'something went wrong', data: null, status: 422,  error: $exception->getMessage());
        }

    }
    public function onGoingOrderList()
    {
        try {
            $order = Order::where('customer_id', auth()->id())
//                ->when(!empty($orderType), function ($query) use ($orderType) {
//                    return $query->where('order_type', $orderType);
//                })
                ->whereIn('status', [Order::$ORDER_STATUS['pending'], Order::$ORDER_STATUS['confirmed'], Order::$ORDER_STATUS['picked']])
                //->where('created_at', '>=', Carbon::now()->subMinutes(5))
                ->latest()
                ->get(); // fetch all matching orders
            $data = MyOrderListResource::collection($order);
            return sendResponse(success: true, message: 'Success My Order List', data: $data);
        }catch (\Exception $exception){
            return sendResponse(false, message: 'something went wrong', data: null, status: 422);
        }
    }
    // only own order request list
    public function myCompletedOrderList()
    {
        try {
            $order = Order::where('customer_id', auth()->id())
                ->where('status', Order::$ORDER_STATUS['delivered'])
                ->with('orderAttempts.bids', 'orderAttempts.bid')
                ->latest()
                ->get();
            $data = MyOrderDetailsResource::collection($order);
            return sendResponse(success: true, message: 'Successfully Get Data', data: $data);
        }catch (\Exception $exception){
            return sendResponse(false, message: 'something went wrong', data: null, status: 422);
        }

    }
    /*
     * orderAttempts.bids means all bids rider will get when order pending.
     * orderAttempts.bid means only one bid get when order confirmed from customer.
     * 1 =  pending, 2 = confirmed, 3 = picked, 4 = shipping
     */
    public function showOrderRequest($orderUniqueId)
    {
        $order = Order::where('order_unique_id', $orderUniqueId)
            ->whereIn('status', [1,2,3,4,5])
            //->where('created_at', '>=', Carbon::now()->subMinutes(5))
            ->with('package:id,name')
            ->with('orderAttempts.bids', 'orderAttempts.bid', 'receiverInformation', 'senderInformation', 'orderDestination', 'package:id,name')->get();
        if(!$order){
            return sendResponse(false, 'Order Not Found', data: null, status: 404);
        }
        try {
            $data =  MyOrderDetailsResource::collection($order);
            return sendResponse(success: true, message: 'My new order list', data: $data);
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'Something went wrong', data: null, status: 422);
        }
    }

    /*
     * orderAttempts.bids means all bids rider will get when order pending.
     * orderAttempts.bid means only one bid get when order confirmed from customer.
     */
    public function myOrderDetails($orderUniqueId){
       $order = Order::where('order_unique_id', $orderUniqueId)
           ->whereIn('status', [Order::$ORDER_STATUS['delivered']])
           //->where('created_at', '>=', Carbon::now()->subMinutes(5))
           ->with('package:id,name')
           ->with('orderAttempts.bids', 'orderAttempts.bid')->get();
       if(!$order){
           return sendResponse(false, 'Order Not Found', data: null, status: 404);
       }
        try {
            $data =  MyOrderDetailsResource::collection($order);
            return sendResponse(success: true, message: 'My new order list', data: $data);
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'Something went wrong', data: null, status: 422);
        }
    }
    // from rider apply bid accepted from customer
    public function orderBidAccept($orderUniqueId, $orderAttemptId, $riderId){

        $order =OrderAttempt::where('order_tracking_number', $orderAttemptId)
           // ->where('created_at', '>=', Carbon::now()->subMinutes(5))
            ->with('order')
            ->firstOrFail();
//         $order = Order::where('order_unique_id', $orderUniqueId)
//             ->where('status', Order::$ORDER_STATUS['pending'])
//             ->where('created_at', '>=', Carbon::now()->subMinutes(5))
//            ->with('bids') // Load bids
//            ->firstOrFail();
        if (!$order){
            return sendResponse(success: false, message: 'Order not found', data: null, status: 404);
        }
         $bid = $order->bid->where('user_id', $riderId)->first();
        if (!$bid){
            return sendResponse(success: false, message: 'Apply Rider Bid not found', data: null, status: 404);
        }

        try {
           DB::transaction(function () use ($order, $bid, $riderId, $orderUniqueId, $orderAttemptId){
//               $order->order()->update([
//                   'rider_id' => $bid->user_id,
//                   'status' => Order::$ORDER_STATUS['confirmed'],
//               ]);
//               $order->update([
//                   'status' => OrderAttempt::$ORDER_STATUS['confirmed'],
//               ]);
               $bid = Bid::where('order_id', $order->order_id)->where('user_id', $riderId)->first();
               $order->update([
                   'fare' => $bid->bid_amount,
               ]);
               $bid->update([
                   'status' => Bid::$STATUS['accepted']
               ]);

            $paymentSecretKey = $this->stripePaymentService->createPaymentIntent($orderUniqueId, $orderAttemptId, $riderId);

            // send notification
                // Get rider tokens
            if ($paymentSecretKey && isset($paymentSecretKey['client_secret'])) {
                $token = DeviceToken::where('user_id', $riderId)->value('device_token');

                if ($token) {
                    app(FcmService::class)->sendToDevice(
                        $token,
                        'Your Bid Accepted ✅',
                        'Your bid has been accepted. Please check the app for details.',
                        'bid_accepted',
                        ['order_id' => $order?->order?->order_unique_id]
                    );
                }
            }
            return sendResponse(success: true, message: 'Order has been confirmed', data: $paymentSecretKey);
           });
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'Something went wrong bid accept', data: null, status: 422);
        }
    }
    public function orderTracking($orderUniqueId)
    {
        try {
            $order = Order::where('order_unique_id', $orderUniqueId)
                ->whereIn('status', [Order::$ORDER_STATUS['confirmed'], Order::$ORDER_STATUS['picked'] , ORder::$ORDER_STATUS['delivered']])
                ->with('receiverInformation', 'bid.user')
                ->firstOrFail();
            $data = new MyOrderDetailsResource($order);
            return sendResponse(success: true, message: 'Order tracking data', data: $data);
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'Something went wrong', data: null, status: 422);
        }

    }
    public function riderOrderSendOtp($orderUniqueId, $otpType){

        $order = Order::where('order_unique_id', $orderUniqueId)
            ->with([
                'customer:id,email',
                'orderAttempt.acceptedBid'
            ])
            ->firstOrFail();
        try {
            $otp = OtpVerify::create([
                'email' => $order?->customer->email,
                'otp_code' => rand(1000, 9999),
                'otp_expires_at' => now()->addMinutes(3),
                'sender_type' => 'email',
                'otp_type' => OtpVerify::$OTP_TYPE[$otpType],
            ]);
            // event(new OtpGenerated(1251)); // Dispatch event
            Mail::to($order?->customer->email)->send(new OtpMail($otp->otp_code));

            return sendResponse(true, 'OTP picked send successfully.');
        }catch (CustomException $e){
            return $e->getMessage();
        }
    }
    public function riderOrderOtpVerify($orderUniqueId, $otpType, $otpCode)
    {

        $order = Order::where('order_unique_id', $orderUniqueId)
            ->with([
                'customer:id,email',
                'orderAttempt.acceptedBid',
                'bid' => function ($query) {
                    $query->where('user_id', auth()->id());
                },
            ])
            ->first();



//        ->with([
//        'orderAttempt' => function ($query) use ($order_tracking_number) {
//            $query->where('order_tracking_number', $order_tracking_number);
//        },
//        'bid' => function ($query) use ($rider_id) {
//            $query->where('user_id', $rider_id);
//        },
   // ])
     //   ->first();

        // otp type check and allow picked and delivered
        if (!in_array($otpType, ['picked', 'delivered'])) {
            return sendResponse(false, 'OTP type allow only picked or delivered', data: null, status: 404);
        }
        // rider check
        if($order->rider_id != auth()->id()){
            return sendResponse(success: false, message: 'Rider not valid', data: null, status: 422);
        }
        $getOTP = OtpVerify::where('email', $order->customer->email)->where('otp_code', $otpCode)->first();

        if (empty($getOTP) ||  $getOTP->otp_code !== $otpCode || Carbon::now()->gt($getOTP->otp_expires_at)) {
            return sendResponse(false, 'Invalid or expired OTP.', null,401);
        }
        try {
            //DB::transaction(function () use ($order, $getOTP, $otpCode, $otpType){
                // email check
                $order->orderAttempt->update([
                    'status' =>  Order::$ORDER_STATUS[$otpType]
                ]);
                $order->update([
                    'status' => Order::$ORDER_STATUS[$otpType]
                ]);
                // clear otp
                $getOTP->delete();

           // });
            if($otpType == 'delivered'){
                $bidAmount = $order?->bid?->bid_amount;
                $pricingRate = PricingRate::where('type', $order->order_type)->first();
                $netFare = abs($bidAmount);
                $wallet = Wallet::where('user_id', $order->rider_id)->first();

                if ($wallet) {
                    $wallet->increment('balance', $netFare);
                }

                WalletHistory::create([
                    'wallet_id' => $wallet->id,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'amount' => $netFare, // reduce platform charge of 10
                    'purpose_of_transaction' => WalletHistory::$PURPOSE_OF_TRANSACTION['order_completed'],
                    'transaction_type' => WalletHistory::$TRANSACTION_TYPE ['credit'],
                ]);
                // send notification
                $orderStatus = Order::$ORDER_STATUS[$otpType];

                $token = DeviceToken::where('user_id', $order->customer_id)
                    ->value('device_token');

                app(FcmService::class)->sendToDevice(
                    $token,
                    "Order {$orderStatus} ✅",
                    "Your order has been {$orderStatus}.",
                    'order_details', // dynamic type
                    [
                        'order_id' => $orderUniqueId,  // ← use real order id
                    ]
                );
            }
            return sendResponse(success: true, message: "OTP {$otpType} send successfully.");
        }catch (\Exception $e){
            return sendResponse(false, "Something went wrong", null, 422, $e->getMessage());
        }

    }
    public function packages()
    {
        try {
            $packages = Package::where('status', 1)->get();
            $data = PackageResource::collection($packages);
            return sendResponse(success: true, message: 'Packages', data: $data);
        }catch (\Exception $e){
            return sendResponse(success: false, message: 'Something went wrong', data: null, status: 422);
        }

    }
    public function zoneList(){
        $distances = DistanceZone::get();
        return sendResponse(success: true, message: 'Successfully get distance data', data: $distances);

    }
    public function weightList(){
       $parcelWeights = WeightRule::get();
        return sendResponse(success: true, message: 'Successfully get weight data', data: $parcelWeights);
    }

}
