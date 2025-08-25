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
    public function show(Order $order)
    {
        $order->load('customer', 'rider', 'bid', 'receiverInformation', 'senderInformation', 'orderDestination', 'package');

        // Get pickup and drop addresses
        $pickupAddress = $this->getAddressFromCoords($order->pickup_latitude, $order->pickup_longitude);
        $dropAddress   = $this->getAddressFromCoords($order->drop_latitude, $order->drop_longitude);

        // Add addresses to order object
        $order->pickup_address = $pickupAddress;
        $order->drop_address   = $dropAddress;

        return Inertia::render('orders/order-details', [
            'order' => $order
        ]);
    }

    private function getAddressFromCoords($lat, $lng)
    {
        // Nominatim requires a User-Agent header
        $opts = [
            "http" => [
                "header" => "User-Agent: MyLaravelApp/1.0\r\n"
            ]
        ];
        $context = stream_context_create($opts);

        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}";
        $response = file_get_contents($url, false, $context);

        if (!$response) {
            return 'Address not found';
        }

        $data = json_decode($response, true);

        return $data['display_name'] ?? 'Address not found';
    }

}
