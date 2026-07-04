<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Donation;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret'));
    }

    public function createCheckoutSession(Donation $donation, string $successUrl, string $cancelUrl): Session
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $donation->project?->title ?? 'Donation',
                        'description' => 'Future Bridge Foundation donation',
                    ],
                    'unit_amount' => (int) round($donation->amount * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'donation_id' => $donation->id,
                'transaction_id' => $donation->transaction_id,
            ],
            'customer_email' => $donation->donor_email,
        ]);
    }

    public function constructEvent(string $payload, string $sigHeader): \Stripe\Event
    {
        return \Stripe\Webhook::constructEvent(
            $payload,
            $sigHeader,
            config('stripe.webhook_secret')
        );
    }
}
