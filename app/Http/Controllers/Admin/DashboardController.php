<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Project;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDonations = Donation::completed()->sum('amount');
        $donationCount = Donation::completed()->count();
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'active')->count();
        $totalUsers = User::count();

        $projectWise = Project::withSum(['donations' => fn($q) => $q->where('status', 'completed')], 'amount')
            ->get()->map(function ($project) {
                return [
                    'project_id' => $project->id,
                    'project_title' => $project->title,
                    'total_donated' => (float) ($project->donations_sum_amount ?? 0),
                    'goal_amount' => (float) $project->goal_amount,
                    'collected_amount' => (float) $project->collected_amount,
                    'donor_count' => $project->donations()->completed()->count(),
                ];
            });

        $monthly = Donation::completed()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total_amount, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $recent = Donation::completed()->with(['user', 'project'])
            ->orderBy('created_at', 'desc')->take(10)->get();

        return response()->json([
            'stats' => [
                'total_donations' => $totalDonations,
                'donation_count' => $donationCount,
                'total_projects' => $totalProjects,
                'active_projects' => $activeProjects,
                'total_users' => $totalUsers,
            ],
            'project_wise_donations' => $projectWise,
            'monthly_donations' => $monthly,
            'recent_donations' => $recent,
        ]);
    }
}
