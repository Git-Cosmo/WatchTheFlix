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
        'price',
        'duration_days',
        'requires_subscription',
        'allowed_plans',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'requires_subscription' => 'boolean',
        'allowed_plans' => 'array',
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
     * Get subscription plans that include this bouquet
     */
    public function subscriptionPlans()
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plan_bouquets');
    }

    /**
     * Scope to active bouquets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if bouquet is free
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
}
