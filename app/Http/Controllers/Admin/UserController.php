<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['donations' => fn($q) => $q->where('status', 'completed')])
            ->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::with(['donations' => fn($q) => $q->with('project')->orderBy('created_at', 'desc')])
            ->findOrFail($id);

        return response()->json($user);
    }
}
