<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::with('project')->orderBy('sort_order')->paginate(20);

        return response()->json($images);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'required|string|max:500',
            'project_id' => 'nullable|exists:projects,id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        return response()->json(GalleryImage::create($request->all()), 201);
    }

    public function update(Request $request, $id)
    {
        $image = GalleryImage::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'sometimes|string|max:500',
            'project_id' => 'nullable|exists:projects,id',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        $image->update($request->all());

        return response()->json($image);
    }

    public function destroy($id)
    {
        GalleryImage::findOrFail($id)->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }
}
