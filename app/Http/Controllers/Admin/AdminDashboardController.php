<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $pendingStatus     = Order::$ORDER_STATUS['pending'];
        $deliveredStatus   = Order::$ORDER_STATUS['delivered'];
        $nationalType      = Order::$ORDER_TYPE['national'];
        $internationalType = Order::$ORDER_TYPE['international'];

        $stats = [
            'total_orders'         => Order::count(),
            'national_orders'      => Order::where('order_type', $nationalType)->count(),
            'international_orders' => Order::where('order_type', $internationalType)->count(),
            'pending_orders'       => Order::where('status', $pendingStatus)->count(),
            'completed_orders'     => Order::where('status', $deliveredStatus)->count(),
            'active_riders' => User::role('rider')->count(),
            'total_customers'      => User::count(),
            // Revenue: sum accepted bid amounts for all delivered orders
            'total_revenue'        => Order::where('status', $deliveredStatus)
                                        ->with('bid')
                                        ->get()
                                        ->sum(fn($o) => $o->bid?->amount ?? 0),
        ];

        $recentOrders = Order::with(['customer', 'rider', 'package', 'bid'])
            ->latest()
            ->take(8)
            ->get()
            ->map(fn($order) => [
                'id'             => $order->id,
                'order_unique_id'=> $order->order_unique_id,
                'customer'       => $order->customer?->name ?? 'N/A',
                'rider'          => $order->rider?->name ?? 'Unassigned',
                'package'        => $order->package?->name ?? 'N/A',
                'type'           => $order->order_type === $nationalType ? 'National' : 'International',
                'status'         => Order::$ORDER_STATUS_NAME[$order->status] ?? 'unknown',
                'payment_status' => Order::$PAYMENT_STATUS_NAME[$order->payment_status ?? 1] ?? 'unpaid',
                'weight'         => $order->weight . ' ' . (Order::$WEIGHT_TYPE_NAME[$order->weight_type] ?? ''),
                'amount'         => $order->bid?->amount ?? 0,
                'created_at'     => $order->created_at->diffForHumans(),
            ]);

        return inertia('dashboard', [
            'stats'        => $stats,
            'recentOrders' => $recentOrders,
        ]);
    }
}
