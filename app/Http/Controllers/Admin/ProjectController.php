<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount(['donations' => fn($q) => $q->where('status', 'completed')])
            ->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:0',
            'featured_image' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,completed,draft',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = Project::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'featured_image' => $request->featured_image,
            'status' => $request->status ?? 'draft',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json($project, 201);
    }

    public function show($id)
    {
        $project = Project::withCount(['donations' => fn($q) => $q->where('status', 'completed')])
            ->findOrFail($id);

        return response()->json($project);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'goal_amount' => 'sometimes|numeric|min:0',
            'featured_image' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,completed,draft',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($request->all());

        if ($request->has('title')) {
            $project->slug = Str::slug($request->title);
            $project->save();
        }

        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
