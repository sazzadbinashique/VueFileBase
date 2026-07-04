<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Mail\DonationReceipt;
use App\Services\SSLCommerzService;
use Illuminate\Http\Request;

class SSLCommerzController extends Controller
{
    public function __construct(
        protected SSLCommerzService $sslcommerz
    ) {}

    public function success(Request $request)
    {
        $valid = $this->sslcommerz->validate($request->val_id);

        if ($valid['status'] === 'VALID' || $valid['status'] === 'VALIDATED') {
            $donation = Donation::where('transaction_id', $request->tran_id)->first();

            if ($donation && $donation->status === 'pending') {
                $donation->update([
                    'status' => 'completed',
                    'val_id' => $request->val_id,
                    'bank_tran_id' => $request->bank_tran_id,
                    'card_type' => $request->card_type,
                    'card_no' => $request->card_no,
                    'card_issuer' => $request->card_issuer,
                    'card_brand' => $request->card_brand,
                    'card_issuer_country' => $request->card_issuer_country,
                    'currency_amount' => $request->currency_amount,
                    'currency_type' => $request->currency_type,
                    'tran_date' => $request->tran_date,
                ]);

                $donation->project?->increment('collected_amount', $donation->amount);
                DonationReceipt::dispatch($donation);
            }

            $frontendUrl = config('app.frontend_url') ?? config('app.url');
            return redirect($frontendUrl . '/donations/success?tran_id=' . $request->tran_id);
        }

        return $this->failed($request);
    }

    public function cancel(Request $request)
    {
        $donation = Donation::where('transaction_id', $request->tran_id)->first();
        if ($donation && $donation->status === 'pending') {
            $donation->update(['status' => 'cancelled']);
        }

        $frontendUrl = config('app.frontend_url') ?? config('app.url');
        return redirect($frontendUrl . '/donations/cancel');
    }

    public function fail(Request $request)
    {
        $donation = Donation::where('transaction_id', $request->tran_id)->first();
        if ($donation && $donation->status === 'pending') {
            $donation->update(['status' => 'failed']);
        }

        $frontendUrl = config('app.frontend_url') ?? config('app.url');
        return redirect($frontendUrl . '/donations/fail');
    }

    public function ipn(Request $request)
    {
        $donation = Donation::where('transaction_id', $request->tran_id)->first();
        if (!$donation || $donation->status !== 'pending') {
            return response('OK', 200);
        }

        $valid = $this->sslcommerz->validate($request->val_id);

        if ($valid['status'] === 'VALID' || $valid['status'] === 'VALIDATED') {
            $donation->update([
                'status' => 'completed',
                'val_id' => $request->val_id,
                'bank_tran_id' => $request->bank_tran_id,
                'card_type' => $request->card_type,
                'card_no' => $request->card_no,
                'card_issuer' => $request->card_issuer,
                'card_brand' => $request->card_brand,
                'card_issuer_country' => $request->card_issuer_country,
                'currency_amount' => $request->currency_amount,
                'currency_type' => $request->currency_type,
                'tran_date' => $request->tran_date,
            ]);

            $donation->project?->increment('collected_amount', $donation->amount);
            DonationReceipt::dispatch($donation);
        }

        return response('OK', 200);
    }
}
