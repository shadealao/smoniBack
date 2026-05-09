<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeController extends Controller
{
    /**
     * Initié un paiement
     */
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount, // en centimes (ex: 1000 = 10€)
            'currency' => 'eur',
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        return response()->json([
            'success' => true,
            'clientSecret' => $paymentIntent->client_secret,
        ], 200);
    }
}
