<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamConnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stream_type',
        'stream_id',
        'ip_address',
        'user_agent',
        'started_at',
        'last_activity_at',
        'ended_at',
        'expires_at',
        'duration',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'ended_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user for this connection
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if connection is active
     */
    public function isActive(): bool
    {
        return is_null($this->ended_at) && $this->expires_at->isFuture();
    }

    /**
     * Scope to active connections
     */
    public function scopeActive($query)
    {
        return $query->whereNull('ended_at')
            ->where('expires_at', '>', now());
    }

    /**
     * Scope to expired connections
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now())
            ->whereNull('ended_at');
    }
}
