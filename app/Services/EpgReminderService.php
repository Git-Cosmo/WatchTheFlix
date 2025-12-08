<?php

namespace App\Services;

use App\Models\EpgReminder;
use App\Models\TvProgram;
use App\Models\User;
use App\Notifications\EpgReminderNotification;
use Illuminate\Support\Collection;

/**
 * EPG Reminder Service
 *
 * Manages TV program reminders and notifications
 */
class EpgReminderService
{
    /**
     * Create a reminder for a TV program
     */
    public function createReminder(
        User $user,
        int $programId,
        int $remindBeforeMinutes = 15,
        string $notificationMethod = 'in_app',
        ?string $notes = null
    ): ?EpgReminder {
        $program = TvProgram::find($programId);

        if (! $program) {
            return null;
        }

        // Calculate reminder time
        $reminderTime = $program->start_time->copy()->subMinutes($remindBeforeMinutes);

        // Don't create if program already started
        if ($reminderTime->isPast()) {
            return null;
        }

        // Check if reminder already exists
        $existing = EpgReminder::where('user_id', $user->id)
            ->where('tv_program_id', $programId)
            ->where('is_sent', false)
            ->first();

        if ($existing) {
            // Update existing reminder
            $existing->update([
                'remind_before_minutes' => $remindBeforeMinutes,
                'reminder_time' => $reminderTime,
                'notification_method' => $notificationMethod,
                'notes' => $notes,
            ]);

            return $existing;
        }

        // Create new reminder
        return EpgReminder::create([
            'user_id' => $user->id,
            'tv_program_id' => $programId,
            'tv_channel_id' => $program->tv_channel_id,
            'remind_before_minutes' => $remindBeforeMinutes,
            'reminder_time' => $reminderTime,
            'notification_method' => $notificationMethod,
            'notes' => $notes,
        ]);
    }

    /**
     * Get user's upcoming reminders
     */
    public function getUserReminders(User $user, int $daysAhead = 7): Collection
    {
        return EpgReminder::with(['program', 'channel'])
            ->forUser($user->id)
            ->pending()
            ->where('reminder_time', '>=', now())
            ->where('reminder_time', '<=', now()->addDays($daysAhead))
            ->orderBy('reminder_time')
            ->get();
    }

    /**
     * Cancel a reminder
     */
    public function cancelReminder(int $reminderId, User $user): bool
    {
        $reminder = EpgReminder::where('id', $reminderId)
            ->where('user_id', $user->id)
            ->first();

        if (! $reminder) {
            return false;
        }

        return $reminder->delete();
    }

    /**
     * Process due reminders and send notifications
     */
    public function processDueReminders(): array
    {
        $reminders = EpgReminder::with(['user', 'program', 'channel'])
            ->dueNow()
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($reminders as $reminder) {
            try {
                $this->sendReminderNotification($reminder);
                $reminder->markAsSent();
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                \Log::error('Failed to send EPG reminder', [
                    'reminder_id' => $reminder->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'processed' => $reminders->count(),
            'sent' => $sent,
            'failed' => $failed,
        ];
    }

    /**
     * Send reminder notification
     */
    protected function sendReminderNotification(EpgReminder $reminder): void
    {
        $user = $reminder->user;
        $program = $reminder->program;
        $channel = $reminder->channel;

        // Create notification data
        $data = [
            'title' => 'Program Reminder',
            'message' => "{$program->title} starts in {$reminder->remind_before_minutes} minutes on {$channel->name}",
            'program' => [
                'id' => $program->id,
                'title' => $program->title,
                'description' => $program->description,
                'start_time' => $program->start_time->toIso8601String(),
                'channel' => [
                    'id' => $channel->id,
                    'name' => $channel->name,
                    'logo' => $channel->logo_url,
                ],
            ],
            'action_url' => route('tv-guide.show', $channel->id),
        ];

        // Send based on notification method
        switch ($reminder->notification_method) {
            case 'email':
                if ($user->email) {
                    $user->notify(new EpgReminderNotification($data));
                }
                break;

            case 'push':
                // TODO: Implement push notifications
                break;

            case 'in_app':
            default:
                // Store in database notifications table
                $user->notify(new EpgReminderNotification($data));
                break;
        }
    }

    /**
     * Get reminder statistics for user
     */
    public function getUserStats(User $user): array
    {
        return [
            'total_reminders' => EpgReminder::forUser($user->id)->count(),
            'pending_reminders' => EpgReminder::forUser($user->id)->pending()->count(),
            'sent_reminders' => EpgReminder::forUser($user->id)->where('is_sent', true)->count(),
            'upcoming_24h' => EpgReminder::forUser($user->id)
                ->pending()
                ->where('reminder_time', '>=', now())
                ->where('reminder_time', '<=', now()->addDay())
                ->count(),
        ];
    }

    /**
     * Bulk create reminders for series/recurring programs
     */
    public function createSeriesReminders(
        User $user,
        string $seriesId,
        int $remindBeforeMinutes = 15,
        string $notificationMethod = 'in_app'
    ): array {
        $programs = TvProgram::where('series_id', $seriesId)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->get();

        $created = 0;
        $skipped = 0;

        foreach ($programs as $program) {
            $reminder = $this->createReminder(
                $user,
                $program->id,
                $remindBeforeMinutes,
                $notificationMethod
            );

            if ($reminder) {
                $created++;
            } else {
                $skipped++;
            }
        }

        return [
            'created' => $created,
            'skipped' => $skipped,
            'total_programs' => $programs->count(),
        ];
    }

    /**
     * Get popular programs (for recommendations)
     */
    public function getPopularPrograms(int $limit = 10): Collection
    {
        return TvProgram::with('channel')
            ->where('start_time', '>', now())
            ->where('start_time', '<', now()->addDays(7))
            ->whereHas('reminders')
            ->withCount('reminders')
            ->orderByDesc('reminders_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Clean up old sent reminders
     */
    public function cleanupOldReminders(int $daysOld = 30): int
    {
        return EpgReminder::where('is_sent', true)
            ->where('sent_at', '<', now()->subDays($daysOld))
            ->delete();
    }

    /**
     * Get reminder recommendations for user based on viewing history
     */
    public function getRecommendedPrograms(User $user, int $limit = 5): Collection
    {
        // Get user's favorite channels from viewing history
        $favoriteChannels = \App\Models\ViewingHistory::where('user_id', $user->id)
            ->whereHas('media', function ($query) {
                $query->where('type', 'live_tv');
            })
            ->select('media_id')
            ->groupBy('media_id')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5)
            ->pluck('media_id');

        // Get upcoming programs on favorite channels
        return TvProgram::with('channel')
            ->whereIn('tv_channel_id', $favoriteChannels)
            ->where('start_time', '>', now())
            ->where('start_time', '<', now()->addDays(7))
            ->where(function ($query) {
                $query->where('is_premiere', true)
                    ->orWhere('rating', '>=', 7.0);
            })
            ->orderBy('start_time')
            ->limit($limit)
            ->get();
    }
}
