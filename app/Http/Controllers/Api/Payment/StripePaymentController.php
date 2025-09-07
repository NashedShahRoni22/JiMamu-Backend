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
        $order = Order::where('order_unique_id', $request->order_id)->first();

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentIntent = PaymentIntent::create([
           // 'amount' => $order->amount * 100, // cents
            'amount' => 1 * 100, // cents
            'currency' => 'usd',
            'metadata' => [
                'order_id' => $order->id
            ],
        ]);

        return sendResponse('success', 'Payment secret key created successfully!', ['client_secret' => $paymentIntent->client_secret], 201);
    }
}
