<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\OrderAttempt;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                $riderId = $paymentIntent->metadata->rider_id ?? null;
                if ($orderId) {

                    $order = Order::where('order_unique_id', $orderId)->with('orderAttempt')->first();
                    Log::info($order);
                    if ($order) {
                        DB::transaction(function () use ($order, $riderId, $paymentIntent){
//                $order->update([
//                    'status' => OrderAttempt::$ORDER_STATUS['confirmed'],
//                ]);
                            $bid = Bid::where('order_id', $order->id)->where('user_id', $riderId)->first();

                            $order->update([

                                'rider_id' => $bid?->user_id,
                                'status' => Order::$ORDER_STATUS['confirmed'],
                            ]);
                            $bid->update([
                                'status' => Bid::$STATUS['accepted']
                            ]);

                            $order->orderAttempt->update([
                                //'fare' => ($paymentIntent->amount / 100),
                                'fare' => $bid?->bid_amount,
                                'status' => OrderAttempt::$ORDER_STATUS['confirmed'],
                                'payment_status' => 2,
                            ]);

                            $wallet = Wallet::where('user_id', $order->customer_id)->with('walletHistory')->first();
//                        $wallet->update([
//                            'balance' => ($wallet->balance + $paymentIntent->amount / 100),
//                        ]);

                            $wallet->walletHistory()->create([
                                'wallet_id' => $wallet->id,
                                'user_id' => $order->customer_id,
                                'order_id' => $order->id,
                                'amount' => ($paymentIntent->amount / 100),
                                'purpose_of_transaction' => WalletHistory::$PURPOSE_OF_TRANSACTION['customer_order_paid'],
                                'transaction_type' => WalletHistory::$TRANSACTION_TYPE ['credit'],
                                'status' => WalletHistory::$STATUS['approved']
                            ]);


                        });
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
