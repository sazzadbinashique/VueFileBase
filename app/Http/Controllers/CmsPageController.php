<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;

class CmsPageController extends Controller
{
    public function show($slug)
    {
        $page = CmsPage::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return response()->json($page);
    }
}
