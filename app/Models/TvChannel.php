<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class TvChannel extends Model
{
    use HasFactory, HasSlug, Searchable;

    protected $fillable = [
        'name',
        'slug',
        'channel_id',
        'country',
        'logo_url',
        'stream_url',
        'channel_number',
        'description',
        'network',
        'owners',
        'categories',
        'website',
        'launched',
        'closed',
        'is_active',
        'is_nsfw',
        'last_synced_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_nsfw' => 'boolean',
        'owners' => 'array',
        'categories' => 'array',
        'launched' => 'date',
        'closed' => 'date',
        'last_synced_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function programs()
    {
        return $this->hasMany(TvProgram::class);
    }

    public function currentPrograms()
    {
        return $this->programs()
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now());
    }

    public function upcomingPrograms()
    {
        return $this->programs()
            ->where('start_time', '>', now())
            ->orderBy('start_time');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
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
            'name' => $this->name,
            'description' => $this->description,
            'country' => $this->country,
            'channel_number' => $this->channel_number,
        ];
    }
}
