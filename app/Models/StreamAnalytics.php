<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stream_type',
        'stream_id',
        'started_at',
        'ended_at',
        'duration',
        'bytes_transferred',
        'quality',
        'buffer_count',
        'buffer_duration',
        'ip_address',
        'country',
        'device_type',
        'user_agent',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Get the user for this analytics record
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the media for this analytics record (polymorphic)
     */
    public function stream()
    {
        if ($this->stream_type === 'live') {
            return TvChannel::find($this->stream_id);
        } elseif ($this->stream_type === 'vod' || $this->stream_type === 'series') {
            return Media::find($this->stream_id);
        }

        return null;
    }

    /**
     * Get formatted bandwidth
     */
    public function getFormattedBandwidthAttribute(): string
    {
        $bytes = $this->bytes_transferred;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        $seconds = $this->duration;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
