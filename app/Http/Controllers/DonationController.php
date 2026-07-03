<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Project;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct()
    {
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

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric|min:1',
            'message' => 'nullable|string|max:1000',
        ]);

        $donation = Donation::create([
            'user_id' => $request->user()->id,
            'project_id' => $request->project_id,
            'amount' => $request->amount,
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'status' => 'completed',
            'donor_name' => $request->user()->name,
            'donor_email' => $request->user()->email,
            'message' => $request->message,
        ]);

        Project::find($request->project_id)->increment('collected_amount', $request->amount);

        return response()->json($donation->load('project'), 201);
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
}
