<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('project')->orderBy('sort_order')->paginate(20);

        return response()->json($videos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
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
            'description' => 'nullable|string',
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
