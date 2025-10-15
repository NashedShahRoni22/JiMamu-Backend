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
                ->with(['orderAttempt' => function ($query) use ($order_tracking_number) {
                    $query->where('order_tracking_number', $order_tracking_number);
                }])
                ->first();
            $pricingRate = PricingRate::where('type', $order->order_type)->first();

            // cutting system base fare and platform change, rider will show only need fare
            $netFare = abs((float)($pricingRate->base_fare + $pricingRate->platform_charge) + (float)$order?->orderAttempt?->fare);

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
