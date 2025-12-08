<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
        'starts_at',
        'expires_at',
        'cancelled_at',
        'payment_method',
        'transaction_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the user for this subscription
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan for this subscription
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->expires_at) {
            return null; // Lifetime
        }
        
        return max(0, now()->diffInDays($this->expires_at, false));
    }

    /**
     * Scope to active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope to expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '<=', now());
    }

    /**
     * Cancel the subscription
     */
    public function cancel(): bool
    {
        $this->status = 'cancelled';
        $this->cancelled_at = now();
        return $this->save();
    }

    /**
     * Renew the subscription
     */
    public function renew(): bool
    {
        if ($this->plan->duration_days > 0) {
            $this->expires_at = now()->addDays($this->plan->duration_days);
        }
        $this->status = 'active';
        $this->cancelled_at = null;
        return $this->save();
    }
}
