<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'published',
        'published_at',
        'image',
        'keywords',
        'views',
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
            $model->slug = Str::slug($model->name);
            $model->user_id = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }


    public static function filter($search)
    {
        return Article::where('title', 'like', "%{$search}%")
            ->orWhere('slug', 'like', "%{$search}%")
            ->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('tags', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orWhere(function ($query) use ($search) {
                if (str_contains(strtolower($search), 'published')) {
                    $query->where('published', 1);
                } elseif (str_contains(strtolower($search), 'draft')) {
                    $query->where('published', 0);
                }
            });
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // one to one
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // many to many
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}