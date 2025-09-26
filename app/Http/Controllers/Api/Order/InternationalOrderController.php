<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\MyOrderListResource;
use App\Models\Order;
use App\Models\OrderAttempt;
use App\Models\OrderDestination;
use App\Models\PricingRate;
use App\Services\Order\FareCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternationalOrderController extends Controller
{
    public function __construct(public FareCalculatorService $fareCalculatorService){

    }
    public function internationalOrderRequest(Request $request)
    {
//        $fare = $this->fareCalculatorService->calculateFare($request->distance, $request->weight);
//        return $fare;
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
                ]);
                $pricingRate = PricingRate::where('type', PricingRate::$STATUS[$request->order_type])->first();
                // cutting system base fare and platform change, rider will show only need fare
                $netFare = abs((float)($pricingRate->base_fare + $pricingRate->platform_charge) - (float)$orderRequest->total_fare);
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
                //$findNearByRiders =  $this->locationService->findNearbyRiders($orderRequest);
            });

            return sendResponse(success: true, message: 'Successfully send order request');
        }catch (\Exception $exception){
            return sendResponse(success: false, message: 'something went wrong', data: null, status: 422,  error: $exception->getMessage());
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
