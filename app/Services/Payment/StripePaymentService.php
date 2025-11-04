<?php
namespace App\Services\Payment;

use App\Models\Order;
use App\Models\PricingRate;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripePaymentService{

    public function createPaymentIntent($order_unique_id, $order_tracking_number, $rider_id)
    {
        try {
            $order = Order::where('order_unique_id', $order_unique_id)
                ->with([
                    'orderAttempt' => function ($query) use ($order_tracking_number) {
                        $query->where('order_tracking_number', $order_tracking_number);
                    },
                    'bid' => function ($query) use ($rider_id) {
                        $query->where('user_id', $rider_id);
                    },
                ])
                ->first();
            //return $order;

            $bidAmount = $order?->bid?->bid_amount;
            $pricingRate = PricingRate::where('type', $order->order_type)->first();

            $netFare = abs(($pricingRate->base_fare + $pricingRate->platform_charge) + $bidAmount);

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
                'amount' => $netFare * 100, // cents (for testing)
                'currency' => 'usd',
                'metadata' => [
                    'rider_id' => $rider_id,
                    'order_id' => $order->order_unique_id,
                    'order_tracker_number' => $orderAttempt->order_unique_id,
                ],
            ]);

            return [
                'order_id' => $order->order_unique_id,
                'order_tracking_number' => $orderAttempt->order_tracking_number,
                'client_secret' => $paymentIntent->client_secret,
            ];

            //return sendResponse('success', 'Payment secret key created successfully!', $orderData, 201);

        } catch (\Exception $e) {
            return sendResponse(false, 'Something went wrong order payment ' . $e->getMessage(), null, 500);
        }
    }


}
