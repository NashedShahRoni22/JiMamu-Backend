<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCancel;
use App\Models\OrderCancelReason;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Models\RiderCancelFlag;
use App\Models\RiderCancelReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderCancelController extends Controller
{
    // from customer order cancel
    public function cancelOrder(Request $request, $order_id){
        $order = Order::where('order_unique_id', $order_id)->where('status', Order::$ORDER_STATUS['confirmed'])->first();
        if(empty($order)){
            return sendResponse(false, 'Order not found/confirmed.', null, 404);
        }
        $orderCancel = OrderCancel::where('order_id', $order->id)->first();
        if($orderCancel){
            return sendResponse(false, 'Order already cancelled', null, 409);
        }
        $wallet = Wallet::where('user_id', auth()->id())->first();
        if(empty($wallet)){
            return sendResponse(false, 'Wallet not found.', null, 404);
        }
        try {
            if($order->status === Order::$ORDER_STATUS['confirmed']){
                DB::transaction(function() use($order, $wallet, $orderCancel, $request){
                    $wallet->decrement('balance', 10);
                    WalletHistory::create([
                        'wallet_id' => $wallet->id,
                        'user_id' => auth()->id(),
                        'order_id' => $order->id,
                        'amount' => 10,
                        'purpose_of_transaction' => WalletHistory::$PURPOSE_OF_TRANSACTION['customer_order_cancel'],
                        'transaction_type' => WalletHistory::$TRANSACTION_TYPE['credit'],
                        'status' => WalletHistory::$STATUS['approved']
                    ]);
                    OrderCancel::create([
                        'order_id' => $order->id,
                        'customer_id' => auth()->id(),
                        'reason' => $request->reason,
                    ]);
                    $order->update([
                        'status' => Order::$ORDER_STATUS['cancelled'],
                    ]);
                });
            }
            return sendResponse(true, 'Successfully Order Has Cancelled.');

        }catch (\Exception $exception){
            return sendResponse(false, 'Something Went Wrong', $exception->getMessage(), 422);
        }
    }
    // from rider order cancel
    public function riderOrderCancel(Request $request, $order_id){
        if (!auth()->user()->hasRole('rider')) {
            return sendResponse(false, 'You are not authorized as a rider.', null, 403);
        }
        $order = Order::where('order_unique_id', $order_id)
            ->where('rider_id', auth()->id())
            ->where('status', Order::$ORDER_STATUS['confirmed'])
            ->with('bid')
            ->first();
        if(empty($order)){
            return sendResponse(false, 'Order not found/assigned.', null, 404);
        }
        $orderCancel = OrderCancel::where('order_id', $order->id)->first();
        if($orderCancel){
            return sendResponse(false, 'Order already cancelled.', null, 409);
        }
        if($order->rider_id != auth()->id()){
            return sendResponse(false, 'Rider not found.', null, 404);
        }
        try {
            // Enforce maximum of 3 rider cancellations (red flags)
            $flagsCount = RiderCancelFlag::where('rider_id', auth()->id())->count();
            if ($flagsCount >= 3) {
                return sendResponse(false, 'You have reached the maximum number of cancellations (3).', null, 403);
            }

            if($order->status === Order::$ORDER_STATUS['confirmed']){
                DB::transaction(function() use($order, $orderCancel, $request){
                    // Record rider cancellation flag (one per order per rider)
                    RiderCancelFlag::firstOrCreate(
                        ['rider_id' => auth()->id(), 'order_id' => $order->id],
                        ['reason' => $request->reason]
                    );

                    OrderCancel::create([
                        'order_id' => $order->id,
                        'customer_id' => auth()->id(),
                        'reason' => $request->reason,
                    ]);
                    $order->update([
                        'status' => Order::$ORDER_STATUS['cancelled'],
                    ]);
                });
            }
            return sendResponse(true, 'Successfully Order Has Cancelled.');
        }catch (\Exception $exception){
            return sendResponse(false, 'Something Went Wrong', null, 422);
        }
    }

    public function orderCancelReason(){
        try {
            $reasons = OrderCancelReason::query()
                ->active()
                ->orderBy('name')
                ->get(['id', 'name']);

            return sendResponse(true, 'Rider cancel reasons fetched successfully.', $reasons);
        } catch (\Exception $e) {
            return sendResponse(false, 'Failed to fetch rider cancel reasons.', null, 500);
        }
    }

}
