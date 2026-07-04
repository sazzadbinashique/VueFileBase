<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Project;
use App\Services\SSLCommerzService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct(
        protected SSLCommerzService $sslcommerz
    ) {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $donations = Donation::with('project')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($donations);
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric|min:10',
            'message' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();
        $transactionId = 'TXN-' . strtoupper(uniqid());

        $donation = Donation::create([
            'user_id' => $user->id,
            'project_id' => $request->project_id,
            'amount' => $request->amount,
            'currency' => 'BDT',
            'transaction_id' => $transactionId,
            'status' => 'pending',
            'donor_name' => $user->name,
            'donor_email' => $user->email,
            'message' => $request->message,
        ]);

        $project = Project::find($request->project_id);

        $response = $this->sslcommerz->initiate([
            'amount' => $request->amount,
            'transaction_id' => $transactionId,
            'cus_name' => $user->name,
            'cus_email' => $user->email,
            'cus_phone' => $user->phone ?? '',
            'product_name' => $project->title ?? 'Donation',
            'product_category' => 'Donation',
        ]);

        if (($response['status'] ?? '') === 'FAILED') {
            $donation->update(['status' => 'failed']);
            return response()->json([
                'message' => 'Payment initiation failed. Please try again.',
                'error' => $response['failedreason'] ?? 'Unknown error',
            ], 422);
        }

        return response()->json([
            'redirect_url' => $response['GatewayPageURL'] ?? $response['redirectGatewayURL'] ?? '',
            'transaction_id' => $transactionId,
        ]);
    }

    public function stats(Request $request)
    {
        $donations = Donation::completed()
            ->where('user_id', $request->user()->id)
            ->with('project')
            ->get();

        $projectWise = $donations->groupBy('project_id')->map(function ($items, $projectId) {
            $project = $items->first()->project;
            return [
                'project_id' => $projectId,
                'project_title' => $project->title ?? 'Unknown',
                'total_amount' => $items->sum('amount'),
                'count' => $items->count(),
            ];
        })->values();

        $monthly = $donations->groupBy(function ($d) {
            return $d->created_at->format('Y-m');
        })->map(function ($items, $month) {
            return [
                'month' => $month,
                'total_amount' => $items->sum('amount'),
                'count' => $items->count(),
            ];
        })->values();

        return response()->json([
            'total_donated' => $donations->sum('amount'),
            'donation_count' => $donations->count(),
            'project_wise' => $projectWise,
            'monthly' => $monthly,
        ]);
    }

    public function yearlyStats(Request $request)
    {
        $donations = Donation::completed()
            ->where('user_id', $request->user()->id)
            ->selectRaw("YEAR(created_at) as year, project_id, COUNT(*) as count, SUM(amount) as total")
            ->groupBy('year', 'project_id')
            ->with('project:id,title')
            ->get();

        $grouped = $donations->groupBy('year')->map(function ($items) {
            return $items->map(function ($item) {
                return [
                    'project_id' => $item->project_id,
                    'project_title' => $item->project?->title ?? 'Unknown',
                    'count' => (int) $item->count,
                    'total' => (float) $item->total,
                ];
            })->values();
        });

        return response()->json($grouped);
    }
}
