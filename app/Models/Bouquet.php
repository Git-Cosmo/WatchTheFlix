<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bouquet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the channels in this bouquet
     */
    public function channels()
    {
        return $this->belongsToMany(TvChannel::class, 'bouquet_channels')
            ->withPivot('position')
            ->orderBy('bouquet_channels.position');
    }

    /**
     * Get users assigned to this bouquet
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_bouquets');
    }

    /**
     * Scope to active bouquets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
