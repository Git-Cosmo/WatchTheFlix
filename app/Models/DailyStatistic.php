<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'total_streams',
        'unique_users',
        'new_users',
        'total_viewing_time',
        'total_bandwidth',
        'peak_concurrent',
        'popular_content',
    ];

    protected $casts = [
        'date' => 'date',
        'popular_content' => 'array',
    ];

    /**
     * Get formatted bandwidth
     */
    public function getFormattedBandwidthAttribute(): string
    {
        $bytes = $this->total_bandwidth;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get formatted viewing time
     */
    public function getFormattedViewingTimeAttribute(): string
    {
        $minutes = $this->total_viewing_time;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$mins}m";
        }
        return "{$mins}m";
    }
}
