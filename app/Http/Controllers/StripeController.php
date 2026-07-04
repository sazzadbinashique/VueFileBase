<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\StripeService;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function __construct(
        protected StripeService $stripe
    ) {}

    public function redirect(Request $request)
    {
        $request->validate([
            'donation_id' => 'required|exists:donations,id',
        ]);

        $donation = Donation::findOrFail($request->donation_id);

        if ($donation->status !== 'pending') {
            return response()->json(['message' => 'Donation already processed.'], 422);
        }

        $frontendUrl = config('app.frontend_url') ?? config('app.url');
        $session = $this->stripe->createCheckoutSession(
            $donation,
            $frontendUrl . '/donations/success?tran_id=' . $donation->transaction_id,
            $frontendUrl . '/donations/cancel'
        );

        $donation->update([
            'stripe_session_id' => $session->id,
        ]);

        return response()->json([
            'redirect_url' => $session->url,
        ]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = $this->stripe->constructEvent($payload, $sigHeader);
        } catch (\Exception $e) {
            return response('Webhook signature verification failed.', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $donation = Donation::where('stripe_session_id', $session->id)->first();

            if ($donation && $donation->status === 'pending') {
                $donation->update([
                    'status' => 'completed',
                    'currency' => 'USD',
                ]);

                $donation->project?->increment('collected_amount', $donation->amount);

                \App\Mail\DonationReceipt::dispatch($donation);
            }
        }

        return response('OK', 200);
    }
}
