<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranscodingJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'quality',
        'status',
        'progress',
        'output_path',
        'error_message',
    ];

    protected $casts = [
        'progress' => 'integer',
    ];

    /**
     * Get the media being transcoded
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /**
     * Check if job is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if job is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if job is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if job has failed
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get human-readable status
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'failed' => 'Failed',
            default => 'Unknown',
        };
    }

    /**
     * Get quality badge color
     */
    public function getQualityColorAttribute(): string
    {
        return match ($this->quality) {
            '360p' => 'gray',
            '480p' => 'blue',
            '720p' => 'green',
            '1080p' => 'purple',
            '4k' => 'red',
            default => 'gray',
        };
    }
}
