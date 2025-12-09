<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ForumThread extends Model
{
    use HasFactory, HasSlug, LogsActivity, Searchable;

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'body',
        'is_pinned',
        'is_locked',
        'views',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'views' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'is_pinned', 'is_locked'])
            ->logOnlyDirty();
    }

    public function category()
    {
        return $this->belongsTo(ForumCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class, 'thread_id');
    }

    public function latestPost()
    {
        return $this->hasOne(ForumPost::class, 'thread_id')->latest();
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'forum_thread_subscriptions', 'thread_id', 'user_id')->withTimestamps();
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function isSubscribedBy($user)
    {
        if (! $user) {
            return false;
        }

        return $this->subscribers()->where('user_id', $user->id)->exists();
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeNotPinned($query)
    {
        return $query->where('is_pinned', false);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'category_name' => $this->category->name ?? null,
            'user_name' => $this->user->name ?? null,
        ];
    }
}
