<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\Order;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $orderId = $paymentIntent->metadata->order_id;

            $order = Order::find($orderId);
            if ($order) {
                $order->status = Order::$ORDER_STATUS['confirmed'];
                $order->save();
            }
           // Wallet::find($order->user_id)->increment('balance', $order->amount);
        }

        return response()->json(['status' => 'success']);
    }
}
