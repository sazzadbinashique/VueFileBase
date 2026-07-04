<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    public function index(Request $request)
    {
        $query = CmsPage::query();

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_bn', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $sortBy = $request->sort_by ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';
        $query->orderBy(in_array($sortBy, ['title', 'status', 'created_at']) ? $sortBy : 'created_at', $sortDir === 'asc' ? 'asc' : 'desc');

        $perPage = min((int)($request->per_page ?? 15), 100);
        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_bn' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'banner_eyebrow' => 'nullable|string|max:255',
            'banner_eyebrow_bn' => 'nullable|string|max:255',
            'banner_title' => 'nullable|string|max:255',
            'banner_title_bn' => 'nullable|string|max:255',
            'banner_description' => 'nullable|string',
            'banner_description_bn' => 'nullable|string',
            'layout_json' => 'nullable|string',
            'layout_json_bn' => 'nullable|string',
            'status' => 'nullable|string|in:published,draft',
        ]);

        $page = CmsPage::create([
            'title' => $request->title,
            'title_bn' => $request->title_bn,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'content_bn' => $request->content_bn,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'banner_eyebrow' => $request->banner_eyebrow,
            'banner_eyebrow_bn' => $request->banner_eyebrow_bn,
            'banner_title' => $request->banner_title,
            'banner_title_bn' => $request->banner_title_bn,
            'banner_description' => $request->banner_description,
            'banner_description_bn' => $request->banner_description_bn,
            'layout_json' => $request->layout_json,
            'layout_json_bn' => $request->layout_json_bn,
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
            'title_bn' => 'nullable|string|max:255',
            'content' => 'sometimes|string',
            'content_bn' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'banner_eyebrow' => 'nullable|string|max:255',
            'banner_eyebrow_bn' => 'nullable|string|max:255',
            'banner_title' => 'nullable|string|max:255',
            'banner_title_bn' => 'nullable|string|max:255',
            'banner_description' => 'nullable|string',
            'banner_description_bn' => 'nullable|string',
            'layout_json' => 'nullable|string',
            'layout_json_bn' => 'nullable|string',
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
