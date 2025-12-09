<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Media extends Model
{
    use HasFactory, HasSlug, HasTags, LogsActivity, Searchable, SoftDeletes;

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
        // SEO fields
        'meta_description',
        'meta_keywords',
        'og_tags',
        'twitter_tags',
        'canonical_url',
        // Enhanced TMDB fields
        'production_companies',
        'production_countries',
        'spoken_languages',
        'budget',
        'revenue',
        'original_title',
        'original_language',
        'status',
        'tagline',
        'popularity',
        'vote_count',
        'vote_average',
        'facebook_id',
        'instagram_id',
        'twitter_id',
        'logos',
        'posters',
        'backdrops',
        'number_of_seasons',
        'number_of_episodes',
        'first_air_date',
        'last_air_date',
        'tmdb_last_synced_at',
    ];

    protected $casts = [
        'genres' => 'array',
        'cast' => 'array',
        'crew' => 'array',
        'subtitles' => 'array',
        'requires_real_debrid' => 'boolean',
        'is_published' => 'boolean',
        'imdb_rating' => 'decimal:1',
        // SEO fields
        'og_tags' => 'array',
        'twitter_tags' => 'array',
        // Enhanced TMDB fields
        'production_companies' => 'array',
        'production_countries' => 'array',
        'spoken_languages' => 'array',
        'popularity' => 'decimal:3',
        'vote_average' => 'decimal:1',
        'logos' => 'array',
        'posters' => 'array',
        'backdrops' => 'array',
        'first_air_date' => 'date',
        'last_air_date' => 'date',
        'tmdb_last_synced_at' => 'datetime',
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

    /**
     * Get the route URL for this media item
     */
    public function getRouteUrl(): string
    {
        if ($this->type === 'movie') {
            return route('media.show', $this->slug);
        }

        // Both 'series' and 'episode' types use the tv-show route structure
        // This maintains a clean URL structure for all episodic content
        return route('media.show.series', $this->slug);
    }

    /**
     * Get the route name for this media type
     */
    public function getRouteName(): string
    {
        if ($this->type === 'movie') {
            return 'media.show';
        }

        // Both 'series' and 'episode' types use the series route name
        // This maintains a clean URL structure for all episodic content
        return 'media.show.series';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'genres' => $this->genres,
            'release_year' => $this->release_year,
            'cast' => $this->cast ? array_column($this->cast, 'name') : [],
            'crew' => $this->crew ? array_column($this->crew, 'name') : [],
            'tags' => $this->tags->pluck('name')->toArray(),
        ];
    }

    /**
     * Get directors from crew data
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDirectors()
    {
        if (!$this->crew || count($this->crew) === 0) {
            return collect([]);
        }

        return collect($this->crew)
            ->filter(function ($person) {
                return isset($person['job']) && $person['job'] === 'Director';
            })
            ->take(3);
    }

    /**
     * Extract YouTube video ID from trailer URL
     *
     * @return string|null
     */
    public function getTrailerYoutubeId(): ?string
    {
        if (!$this->trailer_url) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?\/]+)/', $this->trailer_url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
