<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EducationResource extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'category',
        'summary',
        'content',
        'media_url',
        'language',
        'is_published',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Auto generate the slug if not provided.
     */
    protected static function booted(): void
    {
        static::saving(function (self $resource) {
            if (empty($resource->slug)) {
                $resource->slug = Str::slug($resource->title) . '-' . Str::random(5);
            }
        });
    }
}
