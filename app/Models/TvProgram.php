<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'tv_channel_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'genre',
        'rating',
        'image_url',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function channel()
    {
        return $this->belongsTo(TvChannel::class, 'tv_channel_id');
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
}
