<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::with('project')
            ->active()
            ->when(request('project_id'), fn($q, $id) => $q->where('project_id', $id))
            ->orderBy('sort_order')
            ->paginate(24);

        return response()->json($images);
    }
}
