<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::with('customer')->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'order_unique_id' => $order->order_unique_id,
                'status' => $order->status,
                'customer_name' => $order->customer->name ?? 'N/A',
                'customer_email' => $order->customer->email ?? '',
                'customer_image' => $order->customer->profile_image ?? '',
                'payment_status' => $order->payment_status,
                'weight' => $order->weight,
            ];
        });

        return Inertia::render('orders/index', [
            'orders' => $orders
        ]);
    }
}
