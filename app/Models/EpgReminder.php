<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EpgReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tv_program_id',
        'tv_channel_id',
        'remind_before_minutes',
        'reminder_time',
        'is_sent',
        'sent_at',
        'notification_method',
        'notes',
    ];

    protected $casts = [
        'reminder_time' => 'datetime',
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
        'remind_before_minutes' => 'integer',
    ];

    /**
     * Get the user that owns the reminder
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the TV program for this reminder
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(TvProgram::class, 'tv_program_id');
    }

    /**
     * Get the TV channel for this reminder
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(TvChannel::class, 'tv_channel_id');
    }

    /**
     * Scope for pending reminders (not sent yet)
     */
    public function scopePending($query)
    {
        return $query->where('is_sent', false)
            ->whereNotNull('reminder_time');
    }

    /**
     * Scope for reminders that need to be sent now
     */
    public function scopeDueNow($query)
    {
        return $query->pending()
            ->where('reminder_time', '<=', now());
    }

    /**
     * Scope for a specific user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Mark reminder as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);
    }

    /**
     * Get formatted reminder time
     */
    public function getFormattedReminderTimeAttribute(): string
    {
        return $this->reminder_time ? $this->reminder_time->format('M d, Y H:i') : 'Not set';
    }

    /**
     * Check if reminder is overdue
     */
    public function isOverdue(): bool
    {
        return $this->reminder_time && $this->reminder_time->isPast() && !$this->is_sent;
    }
}
