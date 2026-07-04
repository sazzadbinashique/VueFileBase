<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = [
        'title', 'title_bn', 'description', 'description_bn',
        'image_path', 'project_id', 'sort_order', 'status',
    ];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
