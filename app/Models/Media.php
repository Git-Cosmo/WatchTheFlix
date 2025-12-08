<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Media extends Model
{
    use HasFactory, HasSlug, HasTags, LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'poster_url',
        'backdrop_url',
        'trailer_url',
        'release_year',
        'rating',
        'runtime',
        'imdb_rating',
        'imdb_id',
        'tmdb_id',
        'genres',
        'cast',
        'crew',
        'stream_url',
        'requires_real_debrid',
        'is_published',
    ];

    protected $casts = [
        'genres' => 'array',
        'cast' => 'array',
        'crew' => 'array',
        'subtitles' => 'array',
        'requires_real_debrid' => 'boolean',
        'is_published' => 'boolean',
        'imdb_rating' => 'decimal:1',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'type', 'is_published'])
            ->logOnlyDirty();
    }

    public function watchlistedBy()
    {
        return $this->belongsToMany(User::class, 'watchlists')->withTimestamps();
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function viewingHistory()
    {
        return $this->hasMany(ViewingHistory::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function totalRatings()
    {
        return $this->ratings()->count();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeRequiresRealDebrid($query)
    {
        return $query->where('requires_real_debrid', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'media_platform')
            ->withPivot('availability_url', 'requires_subscription')
            ->withTimestamps();
    }
}
