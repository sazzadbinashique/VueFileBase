<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function show($id)
    {
        return response()->json(Video::with('project')->findOrFail($id));
    }

    public function index(Request $request)
    {
        $query = Video::with('project');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_bn', 'like', "%{$search}%");
            });
        }

        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        $sortBy = $request->sort_by ?? 'sort_order';
        $sortDir = $request->sort_dir ?? 'asc';
        $query->orderBy(in_array($sortBy, ['title', 'created_at', 'sort_order']) ? $sortBy : 'sort_order', $sortDir === 'asc' ? 'asc' : 'desc');

        $perPage = min((int)($request->per_page ?? 20), 100);
        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'url' => 'required|string|max:500',
            'thumbnail' => 'nullable|string|max:500',
            'project_id' => 'nullable|exists:projects,id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        return response()->json(Video::create($request->all()), 201);
    }

    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'url' => 'sometimes|string|max:500',
            'thumbnail' => 'nullable|string|max:500',
            'project_id' => 'nullable|exists:projects,id',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        $video->update($request->all());
        return response()->json($video);
    }

    public function destroy($id)
    {
        Video::findOrFail($id)->delete();
        return response()->json(['message' => 'Video deleted successfully']);
    }
}
