<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, LogsActivity, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'real_debrid_token',
        'real_debrid_enabled',
        'parental_control_enabled',
        'parental_control_pin',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'preferences',
        'current_subscription_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'real_debrid_token',
        'parental_control_pin',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'real_debrid_enabled' => 'boolean',
        'parental_control_enabled' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'two_factor_confirmed_at' => 'datetime',
        'preferences' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'real_debrid_enabled'])
            ->logOnlyDirty();
    }

    public function watchlist()
    {
        return $this->belongsToMany(Media::class, 'watchlists')->withTimestamps();
    }

    public function favorites()
    {
        return $this->belongsToMany(Media::class, 'favorites')->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function viewingHistory()
    {
        return $this->hasMany(ViewingHistory::class);
    }

    public function createdInvites()
    {
        return $this->hasMany(Invite::class, 'created_by');
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function hasRealDebridAccess(): bool
    {
        return $this->real_debrid_enabled && ! empty($this->real_debrid_token);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function currentSubscription()
    {
        return $this->belongsTo(Subscription::class, 'current_subscription_id');
    }

    public function bouquets()
    {
        return $this->belongsToMany(Bouquet::class, 'user_bouquets');
    }

    public function hasActiveSubscription(): bool
    {
        return $this->currentSubscription && $this->currentSubscription->isActive();
    }

    public function getSubscriptionPlan(): ?SubscriptionPlan
    {
        return $this->currentSubscription?->plan;
    }
}
