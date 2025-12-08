<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_days',
        'features',
        'max_connections',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get subscriptions for this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get bouquets included in this plan
     */
    public function bouquets()
    {
        return $this->belongsToMany(Bouquet::class, 'subscription_plan_bouquets');
    }

    /**
     * Scope to active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Check if this is the free plan
     */
    public function isFree(): bool
    {
        return $this->price == 0;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->price == 0 ? 'Free' : '$'.number_format($this->price, 2);
    }

    /**
     * Get duration in human-readable format
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration_days == 0) {
            return 'Lifetime';
        } elseif ($this->duration_days == 30) {
            return '1 Month';
        } elseif ($this->duration_days == 90) {
            return '3 Months';
        } elseif ($this->duration_days == 180) {
            return '6 Months';
        } elseif ($this->duration_days == 365) {
            return '1 Year';
        }

        return $this->duration_days.' Days';
    }
}
