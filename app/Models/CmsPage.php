<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsPage extends Model
{
    protected $fillable = [
        'title', 'title_bn', 'slug', 'content', 'content_bn',
        'meta_title', 'meta_description',
        'banner_eyebrow', 'banner_eyebrow_bn',
        'banner_title', 'banner_title_bn',
        'banner_description', 'banner_description_bn',
        'layout_json', 'layout_json_bn',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
