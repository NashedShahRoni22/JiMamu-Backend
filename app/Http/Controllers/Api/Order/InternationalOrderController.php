<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\MyOrderListResource;
use App\Models\DeviceToken;
use App\Models\Order;
use App\Models\OrderAttempt;
use App\Models\OrderDestination;
use App\Models\PricingRate;
use App\Models\User;
use App\Services\Notifications\FcmService;
use App\Services\Order\FareCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternationalOrderController extends Controller
{
    public function __construct(public FareCalculatorService $fareCalculatorService, public FcmService $fcmService){

    }


public function internationalOrderRequest(Request $request)
{
    // ✅ Validation
    $request->validate([
        'package_id' => 'required|exists:packages,id',
        'pickup_latitude' => 'required|numeric',
        'pickup_longitude' => 'required|numeric',
        'drop_latitude' => 'required|numeric',
        'drop_longitude' => 'required|numeric',
        'weight' => 'required|numeric|min:0',
        'weight_type' => 'required|string',
        'order_type' => 'required|string',
        'total_fare' => 'required|numeric|min:0',
        'parcel_estimate_price' => 'nullable|numeric|min:0',

        // sender তথ্য (optional)
        'sender_information.name' => 'nullable|string|max:255',
        'sender_information.phone_number' => 'nullable|string|max:20',
        'sender_information.remarks' => 'nullable|string|max:500',

        // receiver তথ্য (optional)
        'receiver_information.name' => 'nullable|string|max:255',
        'receiver_information.phone_number' => 'nullable|string|max:20',
        'receiver_information.remarks' => 'nullable|string|max:500',
    ]);

    try {
        DB::transaction(function () use ($request) {

            $orderRequest = Order::create([
                'customer_id' => auth()->id(),
                'order_unique_id' => rand(000000, 999999),
                'package_id' => $request->package_id,
                'order_type' => Order::$ORDER_TYPE['international'],
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
                'fare' => $netFare,
                'parcel_estimate_price' => $request->parcel_estimate_price,
                'order_tracking_number' => rand(000000, 999999),
            ]);

            // when order request will have international
//                if($request->order_type == 'international' && $request->has('order_destination')){
//                    OrderDestination::create([
//                        'order_id' => $orderRequest->id,
//                        'country' => $request->input('order_destination.country'),
//                        'state' => $request->input('order_destination.state'),
//                        'city' =>  $request->input('order_destination.city'),
//                        'area' => $request->input('order_destination.area'),
//                        'address' => $request->input('order_destination.address'),
//
//                    ]);
//                }

            // customer sender informations
            if ($request->has('sender_information')) {
                $senderName = $request->input('sender_information.name');
                $senderPhone = $request->input('sender_information.phone_number');
                $senderRemarks = $request->input('sender_information.remarks');

                $orderRequest->senderInformation()->create([
                    'sender_name' => $senderName,
                    'sender_phone' => $senderPhone,
                    'remarks' => $senderRemarks,
                ]);
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

            //$findNearByRiders =  $this->locationService->findNearbyRiders($orderRequest);

            $ridersId = User::role('rider')->pluck('id');

            $tokens = DeviceToken::whereIn('user_id', $ridersId)
                ->pluck('device_token')
                ->toArray();

            app(FcmService::class)->sendToMultiple(
                $tokens,
                'New Order Created 🛒',
                'A new order has been created. Please check the app for details.',
                'new_order_created',
                ['order_id' => $orderRequest?->order_unique_id]
            );
        });

        return sendResponse(success: true, message: 'Successfully send order request');

    } catch (\Exception $exception) {

        // ❗ If any exception occurs, transaction auto रोलব্যাক হবে
        return sendResponse(
            success: false,
            message: 'something went wrong',
            data: null,
            status: 422,
            error: $exception->getMessage()
        );
    }
}

    public function internationalOngoingOrderList(){
        try {
            $order = Order::where('customer_id', auth()->id())
                ->where('order_type', Order::$ORDER_TYPE['international'])
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
}
