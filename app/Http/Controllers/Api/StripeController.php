<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Models\PaymentIntent;

class StripeController extends Controller
{
    /**
     * Initié un paiement
     */
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'ammount' => $request->amount, // en centimes (ex: 1000 = 10€)
            'currency' => 'eur',
        ]);

        return response()->json([
            'success' => true,
            'clientSecret' => $paymentIntent->client_secret,
        ], 200);
    }
}
