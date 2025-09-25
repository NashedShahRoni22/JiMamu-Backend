<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\OrderAttempt;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Stripe\Webhook;
use App\Models\Order;
use Illuminate\Support\Facades\Log;


class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature', '');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\Exception $e) {
            Log::error('Stripe Webhook signature verification failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
                'headers' => $request->headers->all(),
            ]);

            return response()->json(['error' => $e->getMessage()], 400);
        }

        try {
            if ($event->type === 'payment_intent.succeeded') {
                $paymentIntent = $event->data->object;
                $orderId = $paymentIntent->metadata->order_id ?? null;

                if ($orderId) {
                    $order = Order::where('order_unique_id', $orderId)->with('user', 'orderAttempt')->first();
                    Log::info($order);
                    if ($order) {
//                        $order->status = Order::$ORDER_STATUS['confirmed'];
//                        $order->save();
                        $order->update([
                            'status' => Order::$ORDER_STATUS['confirmed'],
                        ]);

                        $order->orderAttempt->update([
                            'status' => OrderAttempt::$ORDER_STATUS['confirmed'],
                        ]);

                       // $wallet = Wallet::where('user_id', $order->user->id)->with('walletHistory')->first();


                    } else {
                        Log::warning('Stripe Webhook: Order not found', [
                            'order_unique_id' => $orderId,
                            'payment_intent_id' => $paymentIntent->id,
                        ]);
                    }
                } else {
                    Log::warning('Stripe Webhook: Missing order_id in metadata', [
                        'payment_intent_id' => $paymentIntent->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Stripe Webhook processing error', [
                'error' => $e->getMessage(),
                'event' => $event,
            ]);

            return response()->json(['error' => 'Webhook handling failed'], 500);
        }

        return response()->json(['status' => 'success']);
    }

}
