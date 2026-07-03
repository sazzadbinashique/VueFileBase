<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::with(['user', 'project'])
            ->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($donations);
    }

    public function show($id)
    {
        $donation = Donation::with(['user', 'project'])->findOrFail($id);

        return response()->json($donation);
    }
}
