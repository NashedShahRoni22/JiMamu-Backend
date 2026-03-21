<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Services\Notifications\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Stripe\Event;


class StripePaymentController extends Controller
{
    public function __construct(public FcmService $fcmService)
    {
       
    }
    public function stripePaymentProcess($order_id){
        $order = Order::find($order_id);
        if(!$order){
            return sendResponse('false', 'Order not found');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Parcel Delivery Fee'],
                    'unit_amount' => 5000, // $50.00
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => config('app.url') . '/api/v1/payments/stripe/payment/success?session_id={CHECKOUT_SESSION_ID}&orderId=' . $order->id,
            //'success_url' => config('app.url') . '/api/payment/success?session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' => config('app.url') . '/api/payment/cancel',
        ]);
      //  $this->handleStripeWebhook($session);

        return sendResponse(true, 'Payment successful', $session->url, 200);

    }

    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->server('HTTP_STRIPE_SIGNATURE');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $secret);
        } catch (\Exception $e) {
            return response('Webhook Error: ' . $e->getMessage(), 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // You can store this session ID to identify the user
            $sessionId = $session->id;
            $customerEmail = $session->customer_details->email ?? null;

            // ✅ Update your database to mark order as paid
            // For example:
            // Order::where('session_id', $sessionId)->update(['status' => 'paid']);

            \Log::info("Payment succeeded for session: $sessionId");
        }

        return response()->json(['status' => 'success']);
    }
    public function stripePaymentSuccess(Request $request)
    {

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionId = $request->get('session_id');
        $orderId = $request->get('orderId');

        $order = Order::where('id', $orderId)->with('orderAttempt')->first();
        if(!$order){
            return sendResponse('false', 'payment has failed');
        }
        try {
        //    DB::transaction(function () use ($order, $sessionId) {
                // Fetch the session details from Stripe
                $session = Session::retrieve($sessionId);
                if ($session->payment_status === 'paid') {
                    $paymentIntent = PaymentIntent::retrieve($session->payment_intent);
                    // store payment into db
                    $payment = Payment::create([
                        'order_id' => $order->id,
                        'order_attempt_id' => $order?->orderAttempt?->id,
                        'amount' => $paymentIntent->amount / 100,
                        'status' => $paymentIntent->status == 'succeeded' ? Payment::$PAYMENT_STATUS['paid'] : Payment::$PAYMENT_STATUS['unpaid'],
                        // 'payment_method' => $paymentIntent->payment_method,
                    ]);
                    // store into wallet and wallet history
                    $wallet = Wallet::where('user_id', auth()->id())->first();
                   // if ($wallet) {
                        $amount = $wallet?->balance + $paymentIntent->amount / 100;
                        $wallet = Wallet::updateOrCreate(
                            ['user_id' => auth()->id()], // Match by this
                            ['balance' => 50.00]         // Set this
                        );
                        WalletHistory::create([
                            'wallet_id' => $wallet->id,
                            'user_id' => auth()->id(),
                            'order_id' => $order->id,
                            'amount' => $amount,
                            'transaction_type' => WalletHistory::$TRANSACTION_TYPE['credit']
                        ]);

                        // send notifications
                        $ridersId = User::role('rider')->pluck('id');
                        $tokens = DeviceToken::whereIn('user_id', $ridersId)
                            ->pluck('device_token')
                            ->toArray();  // ← must be array

                        // Send
                        app(FcmService::class)->sendToMultiple(
                            $tokens,
                            'New Order Created 🛒',
                            'A new order has been created. Please check the app for details.',
                            'new_order_created',   // type
                            ['order_id' => $order?->order_unique_id]  // extra data
                        );
                        
                    }
              //  }
          //  });

            return sendResponse(true, 'Payment has been successful');

        }catch (\Exception $e){
            return sendResponse(false, $e->getMessage(), null, 403);
        }



    }
}
