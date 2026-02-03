<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MyOrderListResource;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserDashboardController extends Controller
{
    public function userDashboard($riderId)
    {
        $rider = User::findOrFail($riderId);

        // Example: get wallet balance (assuming you have a Wallet model)
        $walletBalance = Wallet::where('user_id', $rider->id)->value('balance') ?? 0;

        //return Order::where('rider_id', $riderId)->get();

        return Inertia::render('users/dashboard/Orders', [
            'auth' => [
                'user' => [
                    'id' => $rider->id,
                    'name' => $rider->name,
                ],
            ],
            'walletBalance' => $walletBalance,
            'riderId' => $rider->id,
            // You can also pass actual rider orders here later:
            // 'orders' => $rider->orders()->latest()->get(),
        ]);
    }
    public function userOrders($riderId){
        try {
            $order = Order::where('customer_id', $riderId)
//                ->when(!empty($orderType), function ($query) use ($orderType) {
//                    return $query->where('order_type', $orderType);
//                })
                ->whereIn('status', [Order::$ORDER_STATUS['pending'], Order::$ORDER_STATUS['confirmed'], Order::$ORDER_STATUS['picked']])
                //->where('created_at', '>=', Carbon::now()->subMinutes(5))
                ->latest()
                ->get(); // fetch all matching orders
            //$data = MyOrderListResource::collection($order);
            $rider = User::findOrFail($riderId);

            // Example: get wallet balance (assuming you have a Wallet model)
            $walletBalance = Wallet::where('user_id', $rider->id)->value('balance') ?? 0;
            return Inertia::render('users/dashboard/UserOrders', [
                'auth' => [
                    'user' => [
                        'id' => $rider->id,
                        'name' => $rider->name,
                    ],
                ],
                'walletBalance' => $walletBalance,
                'riderId' => $rider->id,
                // You can also pass actual rider orders here later:
                // 'orders' => $rider->orders()->latest()->get(),
            ]);
        }catch (\Exception $exception){
            return sendResponse(false, message: 'something went wrong', data: null, status: 422);
        }
    }
}
