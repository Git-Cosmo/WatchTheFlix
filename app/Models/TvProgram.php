<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class TvProgram extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'tv_channel_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'genre',
        'rating',
        'image_url',
        'metadata',
        'series_id',
        'episode_number',
        'season_number',
        'imdb_id',
        'language',
        'is_repeat',
        'is_premiere',
        'cast',
        'director',
        'age_rating',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'metadata' => 'array',
        'is_repeat' => 'boolean',
        'is_premiere' => 'boolean',
    ];

    public function channel()
    {
        return $this->belongsTo(TvChannel::class, 'tv_channel_id');
    }

    public function reminders()
    {
        return $this->hasMany(EpgReminder::class, 'tv_program_id');
    }

    public function isCurrentlyAiring()
    {
        $now = now();

        return $this->start_time <= $now && $this->end_time >= $now;
    }

    public function scopeCurrent($query)
    {
        return $query->where('start_time', '<=', now())
            ->where('end_time', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now())
            ->orderBy('start_time');
    }

    public function scopeByChannel($query, $channelId)
    {
        return $query->where('tv_channel_id', $channelId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_time', [$startDate, $endDate]);
    }

    public function scopePremiere($query)
    {
        return $query->where('is_premiere', true);
    }

    public function scopeHighRated($query, $minRating = 7.0)
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function getDurationAttribute()
    {
        return $this->end_time->diffInMinutes($this->start_time);
    }

    public function getFormattedDurationAttribute()
    {
        $minutes = $this->duration;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return $hours.'h '.$mins.'m';
        }

        return $mins.'m';
    }

    public function getFullTitleAttribute()
    {
        $title = $this->title;

        if ($this->season_number && $this->episode_number) {
            $title .= " - S{$this->season_number}E{$this->episode_number}";
        }

        if ($this->is_repeat) {
            $title .= ' (Repeat)';
        }

        if ($this->is_premiere) {
            $title .= ' (Premiere)';
        }

        return $title;
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
            'genre' => $this->genre,
            'cast' => $this->cast,
            'director' => $this->director,
        ];
    }
}
