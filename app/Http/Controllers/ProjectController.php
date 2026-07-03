<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->when(request('status'), fn($q, $s) => $q->where('status', $s))
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json($projects);
    }

    public function show($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        return response()->json($project);
    }
}
