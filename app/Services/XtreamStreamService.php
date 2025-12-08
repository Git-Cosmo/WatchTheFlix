<?php

namespace App\Services;

use App\Models\Media;
use App\Models\TvChannel;
use App\Models\User;
use App\Models\StreamConnection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Xtream Stream URL Service
 * 
 * Handles stream URL generation, validation, and access control
 */
class XtreamStreamService
{
    /**
     * Generate authenticated stream URL for live TV
     */
    public function generateLiveStreamUrl(User $user, int $channelId, string $format = 'm3u8'): ?string
    {
        $channel = TvChannel::find($channelId);
        
        if (!$channel || !$channel->is_active) {
            return null;
        }

        // Check connection limits
        if (!$this->checkConnectionLimit($user)) {
            return null;
        }

        $token = $this->generateStreamToken($user, 'live', $channelId);
        
        return route('api.xtream.live.stream', [
            'user' => $user->email,
            'pass' => $token,
            'id' => $channelId,
            'ext' => $format
        ]);
    }

    /**
     * Generate authenticated stream URL for VOD
     */
    public function generateVodStreamUrl(User $user, int $mediaId, string $format = 'mp4'): ?string
    {
        $media = Media::find($mediaId);
        
        if (!$media) {
            return null;
        }

        // Check connection limits
        if (!$this->checkConnectionLimit($user)) {
            return null;
        }

        $token = $this->generateStreamToken($user, 'vod', $mediaId);
        
        return route('api.xtream.vod.stream', [
            'user' => $user->email,
            'pass' => $token,
            'id' => $mediaId,
            'ext' => $format
        ]);
    }

    /**
     * Generate timeshift/catch-up stream URL
     */
    public function generateTimeshiftUrl(User $user, int $channelId, int $duration, string $start): ?string
    {
        $channel = TvChannel::find($channelId);
        
        if (!$channel || !config('xtream.enable_timeshift', true)) {
            return null;
        }

        $token = $this->generateStreamToken($user, 'timeshift', $channelId);
        
        return route('api.xtream.timeshift', [
            'user' => $user->email,
            'pass' => $token,
            'duration' => $duration,
            'start' => $start,
            'id' => $channelId
        ]);
    }

    /**
     * Validate stream token
     */
    public function validateStreamToken(string $username, string $token, string $type, int $streamId): bool
    {
        $user = User::where('email', $username)->first();
        
        if (!$user) {
            return false;
        }

        $cacheKey = "stream_token:{$user->id}:{$type}:{$streamId}";
        $cachedToken = Cache::get($cacheKey);
        
        return $cachedToken === $token;
    }

    /**
     * Generate stream access token
     */
    protected function generateStreamToken(User $user, string $type, int $streamId): string
    {
        $token = Str::random(32);
        $lifetime = config('xtream.stream_token_lifetime', 86400); // 24 hours
        
        $cacheKey = "stream_token:{$user->id}:{$type}:{$streamId}";
        Cache::put($cacheKey, $token, $lifetime);
        
        return $token;
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
