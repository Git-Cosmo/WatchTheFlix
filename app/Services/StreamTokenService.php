<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Stream Token Service
 * 
 * Provides enhanced token security with IP binding and time-limited tokens
 */
class StreamTokenService
{
    /**
     * Token lifetime in seconds (default: 2 hours)
     */
    protected const TOKEN_LIFETIME = 7200;

    /**
     * Generate secure stream token with IP binding
     */
    public function generateToken(
        User $user,
        string $type,
        int $streamId,
        string $ipAddress,
        ?int $lifetime = null
    ): string {
        $lifetime = $lifetime ?? self::TOKEN_LIFETIME;
        
        // Generate cryptographically secure token
        $token = hash_hmac(
            'sha256',
            $user->id . '|' . $type . '|' . $streamId . '|' . $ipAddress . '|' . time(),
            config('app.key')
        );
        
        // Store token with metadata
        $cacheKey = "stream_token:{$token}";
        $tokenData = [
            'user_id' => $user->id,
            'type' => $type,
            'stream_id' => $streamId,
            'ip_address' => $ipAddress,
            'user_agent' => request()->userAgent(),
            'created_at' => time(),
            'expires_at' => time() + $lifetime,
        ];
        
        Cache::put($cacheKey, $tokenData, $lifetime);
        
        // Also store reverse lookup for user
        $userTokenKey = "user_tokens:{$user->id}:{$type}:{$streamId}";
        Cache::put($userTokenKey, $token, $lifetime);
        
        return $token;
    }

    /**
     * Validate token with IP binding and expiry check
     */
    public function validateToken(
        string $token,
        string $type,
        int $streamId,
        string $ipAddress
    ): bool {
        $cacheKey = "stream_token:{$token}";
        $tokenData = Cache::get($cacheKey);
        
        if (!$tokenData) {
            return false;
        }
        
        // Check type and stream ID match
        if ($tokenData['type'] !== $type || $tokenData['stream_id'] !== $streamId) {
            return false;
        }
        
        // Check IP address binding (strict mode)
        if (config('streaming.strict_ip_binding', true)) {
            if ($tokenData['ip_address'] !== $ipAddress) {
                return false;
            }
        }
        
        // Check expiry
        if ($tokenData['expires_at'] < time()) {
            Cache::forget($cacheKey);
            return false;
        }
        
        return true;
    }

    /**
     * Validate token and return user
     */
    public function validateAndGetUser(
        string $token,
        string $type,
        int $streamId,
        string $ipAddress
    ): ?User {
        if (!$this->validateToken($token, $type, $streamId, $ipAddress)) {
            return null;
        }
        
        $cacheKey = "stream_token:{$token}";
        $tokenData = Cache::get($cacheKey);
        
        if (!$tokenData) {
            return null;
        }
        
        return User::find($tokenData['user_id']);
    }

    /**
     * Refresh token (extend expiry)
     */
    public function refreshToken(string $token, ?int $additionalTime = null): bool
    {
        $cacheKey = "stream_token:{$token}";
        $tokenData = Cache::get($cacheKey);
        
        if (!$tokenData) {
            return false;
        }
        
        $additionalTime = $additionalTime ?? self::TOKEN_LIFETIME;
        $newExpiry = time() + $additionalTime;
        
        $tokenData['expires_at'] = $newExpiry;
        Cache::put($cacheKey, $tokenData, $additionalTime);
        
        return true;
    }

    /**
     * Revoke specific token
     */
    public function revokeToken(string $token): bool
    {
        $cacheKey = "stream_token:{$token}";
        return Cache::forget($cacheKey);
    }

    /**
     * Revoke all tokens for a user
     */
    public function revokeUserTokens(User $user, ?string $type = null, ?int $streamId = null): int
    {
        $pattern = "stream_token:*";
        $revokedCount = 0;
        
        // Get all token keys
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            $redis = \Illuminate\Support\Facades\Redis::connection(Cache::getStore()->getConnection());
            $keys = $redis->keys("laravel_cache:{$pattern}");
            
            foreach ($keys as $key) {
                $tokenData = Cache::get(str_replace('laravel_cache:', '', $key));
                
                if ($tokenData && $tokenData['user_id'] === $user->id) {
                    // If type specified, only revoke matching tokens
                    if ($type && $tokenData['type'] !== $type) {
                        continue;
                    }
                    
                    // If stream ID specified, only revoke matching tokens
                    if ($streamId && $tokenData['stream_id'] !== $streamId) {
                        continue;
                    }
                    
                    Cache::forget(str_replace('laravel_cache:', '', $key));
                    $revokedCount++;
                }
            }
        }
        
        return $revokedCount;
    }

    /**
     * Get active tokens for user
     */
    public function getUserActiveTokens(User $user): array
    {
        $tokens = [];
        $pattern = "stream_token:*";
        
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            $redis = \Illuminate\Support\Facades\Redis::connection(Cache::getStore()->getConnection());
            $keys = $redis->keys("laravel_cache:{$pattern}");
            
            foreach ($keys as $key) {
                $tokenData = Cache::get(str_replace('laravel_cache:', '', $key));
                
                if ($tokenData && $tokenData['user_id'] === $user->id) {
                    if ($tokenData['expires_at'] >= time()) {
                        $tokens[] = [
                            'token' => str_replace(['laravel_cache:stream_token:', ''], '', $key),
                            'type' => $tokenData['type'],
                            'stream_id' => $tokenData['stream_id'],
                            'ip_address' => $tokenData['ip_address'],
                            'created_at' => date('Y-m-d H:i:s', $tokenData['created_at']),
                            'expires_at' => date('Y-m-d H:i:s', $tokenData['expires_at']),
                            'remaining_seconds' => $tokenData['expires_at'] - time(),
                        ];
                    }
                }
            }
        }
        
        return $tokens;
    }

    /**
     * Generate one-time use token
     */
    public function generateOneTimeToken(
        User $user,
        string $type,
        int $streamId,
        string $ipAddress
    ): string {
        $token = $this->generateToken($user, $type, $streamId, $ipAddress, 300); // 5 minutes
        
        // Mark as one-time use
        $cacheKey = "stream_token:{$token}";
        $tokenData = Cache::get($cacheKey);
        $tokenData['one_time'] = true;
        Cache::put($cacheKey, $tokenData, 300);
        
        return $token;
    }

    /**
     * Validate and consume one-time token
     */
    public function validateAndConsumeOneTimeToken(
        string $token,
        string $type,
        int $streamId,
        string $ipAddress
    ): bool {
        if (!$this->validateToken($token, $type, $streamId, $ipAddress)) {
            return false;
        }
        
        $cacheKey = "stream_token:{$token}";
        $tokenData = Cache::get($cacheKey);
        
        if (!$tokenData || !isset($tokenData['one_time']) || !$tokenData['one_time']) {
            return false;
        }
        
        // Consume the token (delete it)
        Cache::forget($cacheKey);
        
        return true;
    }

    /**
     * Get token statistics
     */
    public function getTokenStats(): array
    {
        $stats = [
            'total_active_tokens' => 0,
            'tokens_by_type' => [],
            'tokens_by_user' => [],
        ];
        
        $pattern = "stream_token:*";
        
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            $redis = \Illuminate\Support\Facades\Redis::connection(Cache::getStore()->getConnection());
            $keys = $redis->keys("laravel_cache:{$pattern}");
            
            foreach ($keys as $key) {
                $tokenData = Cache::get(str_replace('laravel_cache:', '', $key));
                
                if ($tokenData && $tokenData['expires_at'] >= time()) {
                    $stats['total_active_tokens']++;
                    
                    // Count by type
                    $type = $tokenData['type'];
                    if (!isset($stats['tokens_by_type'][$type])) {
                        $stats['tokens_by_type'][$type] = 0;
                    }
                    $stats['tokens_by_type'][$type]++;
                    
                    // Count by user
                    $userId = $tokenData['user_id'];
                    if (!isset($stats['tokens_by_user'][$userId])) {
                        $stats['tokens_by_user'][$userId] = 0;
                    }
                    $stats['tokens_by_user'][$userId]++;
                }
            }
        }
        
        return $stats;
    }

    /**
     * Cleanup expired tokens
     */
    public function cleanupExpiredTokens(): int
    {
        $cleanedCount = 0;
        $pattern = "stream_token:*";
        
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            $redis = \Illuminate\Support\Facades\Redis::connection(Cache::getStore()->getConnection());
            $keys = $redis->keys("laravel_cache:{$pattern}");
            
            foreach ($keys as $key) {
                $tokenData = Cache::get(str_replace('laravel_cache:', '', $key));
                
                if ($tokenData && $tokenData['expires_at'] < time()) {
                    Cache::forget(str_replace('laravel_cache:', '', $key));
                    $cleanedCount++;
                }
            }
        }
        
        return $cleanedCount;
    }
}
