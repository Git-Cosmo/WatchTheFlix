<?php

namespace App\Services;

use App\Models\Media;
use App\Models\StreamConnection;
use App\Models\TvChannel;
use App\Models\User;

/**
 * Xtream Stream URL Service
 *
 * ⚠️ FEATURE ON HOLD: This service is currently postponed until a future release (no ETA).
 *
 * Handles stream URL generation, validation, and access control.
 * Originally enhanced with StreamTokenService for IP-bound secure tokens.
 *
 * See README.md for information about the project's current focus.
 */
class XtreamStreamService
{
    protected StreamTokenService $tokenService;

    public function __construct(StreamTokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Generate authenticated stream URL for live TV - Now with enhanced token security
     */
    public function generateLiveStreamUrl(User $user, int $channelId, string $format = 'm3u8'): ?string
    {
        $channel = TvChannel::find($channelId);

        if (! $channel || ! $channel->is_active) {
            return null;
        }

        // Check connection limits
        if (! $this->checkConnectionLimit($user)) {
            return null;
        }

        // Generate secure IP-bound token
        $ipAddress = request()->ip();
        $token = $this->tokenService->generateToken($user, 'live', $channelId, $ipAddress);

        return route('api.xtream.live.stream', [
            'user' => $user->email,
            'pass' => $token,
            'id' => $channelId,
            'ext' => $format,
        ]);
    }

    /**
     * Generate authenticated stream URL for VOD - Now with enhanced token security
     */
    public function generateVodStreamUrl(User $user, int $mediaId, string $format = 'mp4'): ?string
    {
        $media = Media::find($mediaId);

        if (! $media) {
            return null;
        }

        // Check connection limits
        if (! $this->checkConnectionLimit($user)) {
            return null;
        }

        // Generate secure IP-bound token
        $ipAddress = request()->ip();
        $token = $this->tokenService->generateToken($user, 'vod', $mediaId, $ipAddress);

        return route('api.xtream.vod.stream', [
            'user' => $user->email,
            'pass' => $token,
            'id' => $mediaId,
            'ext' => $format,
        ]);
    }

    /**
     * Generate timeshift/catch-up stream URL - Now with enhanced token security
     */
    public function generateTimeshiftUrl(User $user, int $channelId, int $duration, string $start): ?string
    {
        $channel = TvChannel::find($channelId);

        if (! $channel || ! config('xtream.enable_timeshift', true)) {
            return null;
        }

        // Generate secure IP-bound token
        $ipAddress = request()->ip();
        $token = $this->tokenService->generateToken($user, 'timeshift', $channelId, $ipAddress);

        return route('api.xtream.timeshift', [
            'user' => $user->email,
            'pass' => $token,
            'duration' => $duration,
            'start' => $start,
            'id' => $channelId,
        ]);
    }

    /**
     * Validate stream token - Now using enhanced token service
     */
    public function validateStreamToken(string $username, string $token, string $type, int $streamId): bool
    {
        $ipAddress = request()->ip();

        return $this->tokenService->validateToken($token, $type, $streamId, $ipAddress);
    }

    /**
     * Check if user has reached connection limit
     */
    protected function checkConnectionLimit(User $user): bool
    {
        $maxConnections = $user->max_connections ?? config('xtream.max_connections_per_user', 3);

        $activeConnections = StreamConnection::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->count();

        return $activeConnections < $maxConnections;
    }

    /**
     * Register active stream connection
     */
    public function registerConnection(User $user, string $type, int $streamId, string $ipAddress): StreamConnection
    {
        // End old connections for this user/stream
        StreamConnection::where('user_id', $user->id)
            ->where('stream_type', $type)
            ->where('stream_id', $streamId)
            ->delete();

        return StreamConnection::create([
            'user_id' => $user->id,
            'stream_type' => $type,
            'stream_id' => $streamId,
            'ip_address' => $ipAddress,
            'user_agent' => request()->userAgent(),
            'started_at' => now(),
            'expires_at' => now()->addMinutes(30),
        ]);
    }

    /**
     * Update connection heartbeat
     */
    public function updateConnection(int $connectionId): void
    {
        StreamConnection::where('id', $connectionId)
            ->update([
                'last_activity_at' => now(),
                'expires_at' => now()->addMinutes(30),
            ]);
    }

    /**
     * End stream connection
     */
    public function endConnection(int $connectionId): void
    {
        $connection = StreamConnection::find($connectionId);

        if ($connection) {
            $connection->update([
                'ended_at' => now(),
                'duration' => now()->diffInSeconds($connection->started_at),
            ]);
        }
    }

    /**
     * Get active connections for user
     */
    public function getActiveConnections(User $user): int
    {
        return StreamConnection::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->whereNull('ended_at')
            ->count();
    }

    /**
     * Cleanup expired connections
     */
    public function cleanupExpiredConnections(): int
    {
        return StreamConnection::where('expires_at', '<', now())
            ->whereNull('ended_at')
            ->update([
                'ended_at' => now(),
                'duration' => now()->diffInSeconds('started_at'),
            ]);
    }

    /**
     * Get stream quality options
     */
    public function getQualityOptions(string $streamUrl): array
    {
        return [
            [
                'quality' => 'auto',
                'label' => 'Auto',
                'url' => $streamUrl,
            ],
            [
                'quality' => '1080p',
                'label' => 'Full HD (1080p)',
                'url' => str_replace('.m3u8', '_1080p.m3u8', $streamUrl),
            ],
            [
                'quality' => '720p',
                'label' => 'HD (720p)',
                'url' => str_replace('.m3u8', '_720p.m3u8', $streamUrl),
            ],
            [
                'quality' => '480p',
                'label' => 'SD (480p)',
                'url' => str_replace('.m3u8', '_480p.m3u8', $streamUrl),
            ],
            [
                'quality' => '360p',
                'label' => 'Low (360p)',
                'url' => str_replace('.m3u8', '_360p.m3u8', $streamUrl),
            ],
        ];
    }
}
