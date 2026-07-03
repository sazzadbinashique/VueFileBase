<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    public function index()
    {
        $pages = CmsPage::orderBy('created_at', 'desc')->paginate(20);

        return response()->json($pages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:published,draft',
        ]);

        $page = CmsPage::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->status ?? 'draft',
        ]);

        return response()->json($page, 201);
    }

    public function show($id)
    {
        return response()->json(CmsPage::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $page = CmsPage::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:published,draft',
        ]);

        $page->update($request->all());

        if ($request->has('title')) {
            $page->slug = Str::slug($request->title);
            $page->save();
        }

        return response()->json($page);
    }

    public function destroy($id)
    {
        CmsPage::findOrFail($id)->delete();

        return response()->json(['message' => 'Page deleted successfully']);
    }
}
