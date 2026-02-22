<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MyOrderListResource;
use App\Http\Resources\Wallet\WalletResource;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserDashboardController extends Controller
{
    public function userDashboard($riderId)
    {
        $rider = User::findOrFail($riderId);

        $walletBalance = Wallet::where('user_id', $rider->id)->value('balance') ?? 0;

        $orders = Order::where('rider_id', $riderId)
            ->with([
                'orderAttempt' => function ($q) {
                    $q->latest()->limit(1);
                },
                'rider:id,name,email', // customer relation must exist
            ])
            ->latest()
            ->get();

        return Inertia::render('usersReport/dashboard/Orders', [
            // 👇 Admin review করছে, তাই auth user optional
            'auth' => [
                'user' => auth()->check() ? [
                    'id' => auth()->id(),
                    'name' => auth()->user()->name,
                ] : null,
            ],

            'walletBalance' => $walletBalance,
            'riderId' => $rider->id,
            'rider' => [
                'id' => $rider->id,
                'name' => $rider->name,
                'email' => $rider->email,
            ],
            'orders' => $orders
        ]);
    }
    public function requestedOrder($riderId)
    {
        $rider = User::findOrFail($riderId);

        $walletBalance = Wallet::where('user_id', $rider->id)->value('balance') ?? 0;

         $orders = Order::where('customer_id', $riderId)
            ->with([
                'orderAttempt' => function ($q) {
                    $q->latest()->limit(1);
                },
                'customer:id,name,email', // customer relation must exist
            ])
            ->latest()
            ->get();

        return Inertia::render('usersReport/dashboard/RequestedOrder', [
            // 👇 Admin review করছে, তাই auth user optional
            'auth' => [
                'user' => auth()->check() ? [
                    'id' => auth()->id(),
                    'name' => auth()->user()->name,
                ] : null,
            ],

            'walletBalance' => $walletBalance,
            'riderId' => $rider->id,
            'rider' => [
                'id' => $rider->id,
                'name' => $rider->name,
                'email' => $rider->email,
            ],
            'orders' => $orders
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
            return Inertia::render('usersReport/dashboard/UserOrders', [
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
    public function wallet($user_id)
    {

        try {
            // Fetch user to make sure user exists
            $user = User::with('bankInformation')->findOrFail($user_id);
            // Fetch wallet and wallet history
            $wallet = Wallet::where('user_id', $user_id)
                ->with(['walletHistory' => function ($query) {
                    $query->whereIn('status', [1, 2, 3, 4]) // only valid statuses
                    ->orderBy('created_at', 'desc');
                }])
                ->first();

            // Provide safe defaults in case wallet not found
            $walletData = [
                'balance' => $wallet?->balance ?? '0.00',
                'walletHistory' => $wallet?->walletHistory->map(function ($item) {
                        return [
                            'amount' => $item->amount,
                            'purpose_of_transaction' => WalletHistory::$PURPOSE_OF_TRANSACTION_NAME[$item->purpose_of_transaction] ?? 'Unknown',
                            'transaction_type' => WalletHistory::$TRANSACTION_TYPE_NAME[$item->transaction_type] ?? 'Unknown',
                            'status' => WalletHistory::$STATUS_NAME[$item->status] ?? 'Unknown',
                            'created_at' => $item->created_at->format('d-m-Y h:i A'),
                        ];
                    })->toArray() ?? [],
            ];

            return Inertia::render('usersReport/dashboard/Wallet', [
                'wallet' => $walletData,
                'riderId' => $user->id,
                'walletBalance' => $walletData['balance'],
                'bankInformation' => $user?->bankInformation
            ]);
        } catch (\Exception $e) {
            // Optional: log exception
            \Log::error("Wallet fetch error: " . $e->getMessage());

            // Return safe defaults to React
            return Inertia::render('usersReport/dashboard/Wallet', [
                'wallet' => [
                    'balance' => '0.00',
                    'walletHistory' => [],
                ],
                'riderId' => $user_id,
                'walletBalance' => '0.00',
            ]);
        }
    }

}
