<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'title', 'title_bn', 'slug', 'icon', 'color',
        'description', 'short_en', 'short_bn',
        'body_en', 'body_bn', 'impact_en', 'impact_bn',
        'goal_amount', 'collected_amount',
        'status', 'featured_image', 'start_date', 'end_date',
    ];

    protected function casts(): array
    {
        return [
            'goal_amount' => 'decimal:2',
            'collected_amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'body_en' => 'array',
            'body_bn' => 'array',
            'impact_en' => 'array',
            'impact_bn' => 'array',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function galleryImages()
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getProgressAttribute()
    {
        if ($this->goal_amount > 0) {
            return min(100, round(($this->collected_amount / $this->goal_amount) * 100, 1));
        }
        return 0;
    }
}
