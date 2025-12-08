<?php

namespace App\Services;

use App\Models\Media;
use App\Models\TvChannel;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * Stream Cache Service
 * 
 * Provides Redis-based caching for stream data to achieve 10-100x performance improvements
 */
class StreamCacheService
{
    /**
     * Cache TTL for different types of data (in seconds)
     */
    protected const CACHE_TTL = [
        'live_streams' => 300,      // 5 minutes
        'vod_streams' => 3600,      // 1 hour
        'series_streams' => 3600,   // 1 hour
        'categories' => 1800,       // 30 minutes
        'epg' => 900,               // 15 minutes
        'user_auth' => 86400,       // 24 hours
        'stream_token' => 3600,     // 1 hour
    ];

    /**
     * Cache live streams with Redis
     */
    public function cacheLiveStreams(?string $category = null): array
    {
        $cacheKey = "xtream:live_streams" . ($category ? ":{$category}" : '');
        
        return Cache::remember($cacheKey, self::CACHE_TTL['live_streams'], function () use ($category) {
            $query = TvChannel::active();

            if ($category) {
                $query->where('country', $category);
            }

            return $query->get()->map(function ($channel) {
                return [
                    'num' => $channel->id,
                    'name' => $channel->name,
                    'stream_type' => 'live',
                    'stream_id' => $channel->id,
                    'stream_icon' => $channel->logo_url,
                    'epg_channel_id' => $channel->slug,
                    'added' => $channel->created_at->timestamp,
                    'category_id' => $channel->country,
                    'custom_sid' => '',
                    'tv_archive' => 1,
                    'direct_source' => '',
                    'tv_archive_duration' => 7,
                ];
            })->toArray();
        });
    }

    /**
     * Cache VOD streams with Redis
     */
    public function cacheVodStreams(?string $category = null): array
    {
        $cacheKey = "xtream:vod_streams" . ($category ? ":{$category}" : '');
        
        return Cache::remember($cacheKey, self::CACHE_TTL['vod_streams'], function () use ($category) {
            $query = Media::published();

            if ($category) {
                $query->where('type', $category);
            }

            return $query->get()->map(function ($media) {
                return [
                    'num' => $media->id,
                    'name' => $media->title,
                    'stream_type' => 'movie',
                    'stream_id' => $media->id,
                    'stream_icon' => $media->poster_url,
                    'rating' => $media->imdb_rating ?? 0,
                    'rating_5based' => $media->imdb_rating ? round($media->imdb_rating / 2, 1) : 0,
                    'added' => $media->created_at->timestamp,
                    'category_id' => $media->type,
                    'container_extension' => 'mp4',
                    'custom_sid' => '',
                    'direct_source' => $media->stream_url ?? '',
                ];
            })->toArray();
        });
    }

    /**
     * Cache categories with Redis
     */
    public function cacheCategories(string $type = 'live'): array
    {
        $cacheKey = "xtream:categories:{$type}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL['categories'], function () use ($type) {
            if ($type === 'live') {
                return TvChannel::active()
                    ->select('country')
                    ->distinct()
                    ->get()
                    ->map(function ($item) {
                        return [
                            'category_id' => $item->country,
                            'category_name' => $item->country . ' Channels',
                            'parent_id' => 0
                        ];
                    })->toArray();
            } elseif ($type === 'vod') {
                return [
                    ['category_id' => 'movie', 'category_name' => 'Movies', 'parent_id' => 0],
                    ['category_id' => 'series', 'category_name' => 'TV Series', 'parent_id' => 0]
                ];
            }

            return [];
        });
    }

    /**
     * Cache EPG data with Redis
     */
    public function cacheEpg(int $streamId, ?int $limit = null): array
    {
        $cacheKey = "xtream:epg:{$streamId}" . ($limit ? ":{$limit}" : '');
        
        return Cache::remember($cacheKey, self::CACHE_TTL['epg'], function () use ($streamId, $limit) {
            $channel = TvChannel::find($streamId);

            if (!$channel) {
                return [];
            }

            $query = \App\Models\TvProgram::where('tv_channel_id', $streamId)
                ->where('start_time', '>=', now())
                ->orderBy('start_time');

            if ($limit) {
                $query->limit($limit);
            }

            return $query->get()->map(function ($program) {
                return [
                    'id' => $program->id,
                    'epg_id' => $program->tv_channel_id,
                    'title' => $program->title,
                    'lang' => 'en',
                    'start' => $program->start_time->format('Y-m-d H:i:s'),
                    'end' => $program->end_time->format('Y-m-d H:i:s'),
                    'description' => $program->description,
                    'channel_id' => $program->tv_channel_id,
                    'start_timestamp' => $program->start_time->timestamp,
                    'stop_timestamp' => $program->end_time->timestamp,
                ];
            })->toArray();
        });
    }

    /**
     * Cache user authentication
     */
    public function cacheUserAuth(string $username, array $data): void
    {
        $cacheKey = "xtream:user_auth:{$username}";
        Cache::put($cacheKey, $data, self::CACHE_TTL['user_auth']);
    }

    /**
     * Get cached user authentication
     */
    public function getCachedUserAuth(string $username): ?array
    {
        $cacheKey = "xtream:user_auth:{$username}";
        return Cache::get($cacheKey);
    }

    /**
     * Invalidate cache for specific type
     */
    public function invalidate(string $type, ?string $identifier = null): void
    {
        if ($identifier) {
            Cache::forget("{$type}:{$identifier}");
        } else {
            // Clear all keys matching the pattern using Redis directly
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $pattern = "laravel_cache:{$type}:*";
                $redis = Redis::connection(Cache::getStore()->getConnection());
                $keys = $redis->keys($pattern);
                
                if (!empty($keys)) {
                    $redis->del($keys);
                }
            }
        }
    }

    /**
     * Warm up cache for commonly accessed data
     */
    public function warmup(): array
    {
        $results = [
            'live_streams' => $this->cacheLiveStreams(),
            'vod_streams' => $this->cacheVodStreams(),
            'live_categories' => $this->cacheCategories('live'),
            'vod_categories' => $this->cacheCategories('vod'),
        ];

        return [
            'success' => true,
            'cached_items' => [
                'live_streams' => count($results['live_streams']),
                'vod_streams' => count($results['vod_streams']),
                'live_categories' => count($results['live_categories']),
                'vod_categories' => count($results['vod_categories']),
            ]
        ];
    }

    /**
     * Get cache statistics
     */
    public function getStats(): array
    {
        $stats = [
            'cache_driver' => config('cache.default'),
            'redis_connected' => false,
            'total_keys' => 0,
            'memory_usage' => 0,
        ];

        try {
            if (config('cache.default') === 'redis') {
                $redis = Redis::connection();
                $stats['redis_connected'] = true;
                
                // Get keys matching our pattern
                $pattern = "laravel_cache:xtream:*";
                $keys = $redis->keys($pattern);
                $stats['total_keys'] = count($keys);
                
                // Get memory info
                $info = $redis->info('memory');
                $stats['memory_usage'] = $info['used_memory_human'] ?? 'N/A';
            }
        } catch (\Exception $e) {
            $stats['error'] = $e->getMessage();
        }

        return $stats;
    }

    /**
     * Clear all Xtream API caches
     */
    public function clearAll(): bool
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $pattern = "laravel_cache:xtream:*";
                $redis = Redis::connection(Cache::getStore()->getConnection());
                $keys = $redis->keys($pattern);
                
                if (!empty($keys)) {
                    $redis->del($keys);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
