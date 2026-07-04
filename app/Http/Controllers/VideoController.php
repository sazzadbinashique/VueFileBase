<?php

namespace App\Http\Controllers;

use App\Models\Video;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('project')
            ->active()
            ->when(request('project_id'), fn($q, $id) => $q->where('project_id', $id))
            ->orderBy('sort_order')
            ->paginate(12);

        return response()->json($videos);
    }
}
