<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Order;

class StripePaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        try {
            $order = Order::where('order_unique_id', $request->order_id)
                ->with(['orderAttempt' => function ($query) use ($request) {
                    $query->where('order_tracking_number', $request->order_tracking_number);
                }])
                ->first();

            if (!$order) {
                return sendResponse(false, 'Order not found!', null, 404);
            }

            // Access orderAttempt safely
            $orderAttempt = $order->orderAttempt; // no first(), use as-is

            if (!$orderAttempt) {
                return sendResponse(false, 'Order tracking number not found!', null, 404);
            }

            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => 1 * 100, // cents (for testing)
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $order->order_unique_id,
                    'order_tracker_number' => $orderAttempt->order_unique_id,
                ],
            ]);

            $orderData = [
                'order_id' => $order->order_unique_id,
                'order_tracking_number' => $orderAttempt->order_tracking_number,
                'client_secret' => $paymentIntent->client_secret,
            ];

            return sendResponse('success', 'Payment secret key created successfully!', $orderData, 201);

        } catch (\Exception $e) {
            return sendResponse(false, 'Something went wrong: ' . $e->getMessage(), null, 500);
        }
    }

}
