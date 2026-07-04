<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::withCount(['donations' => fn($q) => $q->where('status', 'completed')]);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_bn', 'like', "%{$search}%")
                  ->orWhere('short_en', 'like', "%{$search}%")
                  ->orWhere('short_bn', 'like', "%{$search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $sortBy = $request->sort_by ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';
        $query->orderBy(in_array($sortBy, ['title', 'status', 'goal_amount', 'created_at']) ? $sortBy : 'created_at', $sortDir === 'asc' ? 'asc' : 'desc');

        $perPage = min((int)($request->per_page ?? 15), 100);
        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description' => 'required|string',
            'short_en' => 'nullable|string',
            'short_bn' => 'nullable|string',
            'body_en' => 'nullable|json',
            'body_bn' => 'nullable|json',
            'impact_en' => 'nullable|json',
            'impact_bn' => 'nullable|json',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'goal_amount' => 'required|numeric|min:0',
            'featured_image' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,completed,draft',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = Project::create([
            'title' => $request->title,
            'title_bn' => $request->title_bn,
            'slug' => Str::slug($request->title),
            'icon' => $request->icon ?? 'heart',
            'color' => $request->color ?? 'primary',
            'description' => $request->description,
            'short_en' => $request->short_en,
            'short_bn' => $request->short_bn,
            'body_en' => $request->body_en ? json_decode($request->body_en) : null,
            'body_bn' => $request->body_bn ? json_decode($request->body_bn) : null,
            'impact_en' => $request->impact_en ? json_decode($request->impact_en) : null,
            'impact_bn' => $request->impact_bn ? json_decode($request->impact_bn) : null,
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
            'title_bn' => 'nullable|string|max:255',
            'description' => 'sometimes|string',
            'short_en' => 'nullable|string',
            'short_bn' => 'nullable|string',
            'body_en' => 'nullable|json',
            'body_bn' => 'nullable|json',
            'impact_en' => 'nullable|json',
            'impact_bn' => 'nullable|json',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'goal_amount' => 'sometimes|numeric|min:0',
            'featured_image' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,completed,draft',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        if ($request->has('body_en')) $data['body_en'] = json_decode($request->body_en);
        if ($request->has('body_bn')) $data['body_bn'] = json_decode($request->body_bn);
        if ($request->has('impact_en')) $data['impact_en'] = json_decode($request->impact_en);
        if ($request->has('impact_bn')) $data['impact_bn'] = json_decode($request->impact_bn);

        $project->update($data);

        if ($request->has('title')) {
            $project->slug = Str::slug($request->title);
            $project->save();
        }

        return response()->json($project);
    }

    public function destroy($id)
    {
        Project::findOrFail($id)->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }
}
