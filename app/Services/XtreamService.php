<?php

namespace App\Services;

use App\Models\Media;
use App\Models\TvChannel;
use App\Models\TvProgram;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Xtream Codes API Service
 * 
 * ⚠️ FEATURE ON HOLD: This service is currently postponed until a future release (no ETA).
 * 
 * Provides Xtream Codes-compatible API functionality for IPTV applications.
 * The code remains available for reference and future development, but is not
 * actively maintained or recommended for production use at this time.
 * 
 * See README.md for information about the project's current focus on
 * TMDB-based content catalog and TV Guide features.
 * 
 * Originally enhanced with Redis caching for 10-100x performance improvement.
 */
class XtreamService
{
    protected StreamCacheService $cacheService;

    public function __construct(StreamCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Authenticate user and return credentials
     */
    public function authenticate(string $username, string $password): ?array
    {
        // Check cache first
        $cached = $this->cacheService->getCachedUserAuth($username);
        if ($cached) {
            // Verify password still matches
            $user = User::where('email', $username)->first();
            if ($user && Hash::check($password, $user->password)) {
                return $cached;
            }
        }
        $user = User::where('email', $username)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        // Generate or retrieve auth token
        $token = $user->createToken('xtream-api')->plainTextToken;

        $authData = [
            'user_info' => [
                'username' => $user->email,
                'password' => $token,
                'message' => 'Welcome to WatchTheFlix',
                'auth' => 1,
                'status' => 'Active',
                'exp_date' => null,
                'is_trial' => '0',
                'active_cons' => '0',
                'created_at' => $user->created_at->timestamp,
                'max_connections' => '1',
                'allowed_output_formats' => ['m3u8', 'ts']
            ],
            'server_info' => [
                'url' => config('app.url'),
                'port' => '80',
                'https_port' => '443',
                'server_protocol' => 'https',
                'rtmp_port' => '1935',
                'timezone' => config('app.timezone'),
                'timestamp_now' => now()->timestamp,
                'time_now' => now()->format('Y-m-d H:i:s')
            ]
        ];

        // Cache the auth data
        $this->cacheService->cacheUserAuth($username, $authData);

        return $authData;
    }

    /**
     * Get live streams (TV channels) - Now cached for 10-100x performance
     */
    public function getLiveStreams(?string $category = null): array
    {
        return $this->cacheService->cacheLiveStreams($category);
    }

    /**
     * Get live stream categories - Now cached
     */
    public function getLiveCategories(): array
    {
        return $this->cacheService->cacheCategories('live');
    }

    /**
     * Get VOD streams (movies/series) - Now cached
     */
    public function getVodStreams(?string $category = null): array
    {
        return $this->cacheService->cacheVodStreams($category);
    }

    /**
     * Get VOD categories - Now cached
     */
    public function getVodCategories(): array
    {
        return $this->cacheService->cacheCategories('vod');
    }

    /**
     * Get VOD info
     */
    public function getVodInfo(int $vodId): ?array
    {
        $media = Media::find($vodId);

        if (!$media) {
            return null;
        }

        return [
            'info' => [
                'tmdb_id' => $media->tmdb_id,
                'name' => $media->title,
                'cover' => $media->poster_url,
                'plot' => $media->description,
                'cast' => is_array($media->cast) ? implode(', ', $media->cast) : '',
                'director' => '',
                'genre' => is_array($media->genres) ? implode(', ', $media->genres) : '',
                'release_date' => $media->release_year,
                'last_modified' => $media->updated_at->timestamp,
                'rating' => $media->imdb_rating ?? 0,
                'rating_5based' => $media->imdb_rating ? round($media->imdb_rating / 2, 1) : 0,
                'backdrop_path' => [$media->backdrop_url],
                'youtube_trailer' => $media->trailer_url ?? '',
                'episode_run_time' => $media->runtime ?? 0,
                'category_id' => $media->type,
            ],
            'movie_data' => [
                'stream_id' => $media->id,
                'name' => $media->title,
                'added' => $media->created_at->timestamp,
                'category_id' => $media->type,
                'container_extension' => 'mp4',
                'custom_sid' => '',
                'direct_source' => $media->stream_url ?? '',
            ]
        ];
    }

    /**
     * Get series info
     */
    public function getSeriesInfo(int $seriesId): ?array
    {
        $media = Media::where('type', 'series')->find($seriesId);

        if (!$media) {
            return null;
        }

        return [
            'seasons' => [],
            'info' => [
                'name' => $media->title,
                'cover' => $media->poster_url,
                'plot' => $media->description,
                'cast' => is_array($media->cast) ? implode(', ', $media->cast) : '',
                'director' => '',
                'genre' => is_array($media->genres) ? implode(', ', $media->genres) : '',
                'release_date' => $media->release_year,
                'last_modified' => $media->updated_at->timestamp,
                'rating' => $media->imdb_rating ?? 0,
                'rating_5based' => $media->imdb_rating ? round($media->imdb_rating / 2, 1) : 0,
                'backdrop_path' => [$media->backdrop_url],
                'youtube_trailer' => $media->trailer_url ?? '',
                'episode_run_time' => $media->runtime ?? 0,
                'category_id' => 'series',
            ],
            'episodes' => []
        ];
    }

    /**
     * Get series categories (Xtream format)
     */
    public function getSeriesCategories(): array
    {
        return [
            [
                'category_id' => 'series',
                'category_name' => 'TV Series',
                'parent_id' => 0
            ]
        ];
    }

    /**
     * Get all series streams
     */
    public function getSeriesStreams(?string $category = null): array
    {
        $query = Media::where('type', 'series')->published();

        return $query->get()->map(function ($media) {
            return [
                'num' => $media->id,
                'name' => $media->title,
                'series_id' => $media->id,
                'cover' => $media->poster_url,
                'plot' => $media->description,
                'cast' => is_array($media->cast) ? implode(', ', $media->cast) : '',
                'director' => '',
                'genre' => is_array($media->genres) ? implode(', ', $media->genres) : '',
                'release_date' => $media->release_year,
                'last_modified' => $media->updated_at->timestamp,
                'rating' => $media->imdb_rating ?? 0,
                'rating_5based' => $media->imdb_rating ? round($media->imdb_rating / 2, 1) : 0,
                'backdrop_path' => [$media->backdrop_url],
                'youtube_trailer' => $media->trailer_url ?? '',
                'episode_run_time' => $media->runtime ?? 0,
                'category_id' => 'series',
            ];
        })->toArray();
    }

    /**
     * Get short EPG for live streams (Xtream format) - Now cached
     */
    public function getShortEpg(int $streamId, ?int $limit = null): array
    {
        return $this->cacheService->cacheEpg($streamId, $limit);
    }

    /**
     * Generate M3U playlist
     */
    public function generateM3U(User $user): string
    {
        $token = $user->tokens()->where('name', 'xtream-api')->first();
        $authToken = $token ? $token->token : '';
        
        $baseUrl = config('app.url');
        $m3u = "#EXTM3U\n";
        $m3u .= "#EXTINF:-1 tvg-logo=\"\" group-title=\"WatchTheFlix\",WatchTheFlix Channels\n\n";

        // Add live TV channels
        $channels = TvChannel::active()->get();
        foreach ($channels as $channel) {
            $m3u .= "#EXTINF:-1 tvg-id=\"{$channel->slug}\" tvg-name=\"{$channel->name}\" tvg-logo=\"{$channel->logo_url}\" group-title=\"{$channel->country}\",{$channel->name}\n";
            $m3u .= "{$baseUrl}/api/xtream/live/{$user->email}/{$authToken}/{$channel->id}.m3u8\n\n";
        }

        // Add VOD content
        $media = Media::published()->limit(100)->get();
        foreach ($media as $item) {
            $m3u .= "#EXTINF:-1 tvg-logo=\"{$item->poster_url}\" group-title=\"{$item->type}\",{$item->title}\n";
            $m3u .= "{$baseUrl}/api/xtream/vod/{$user->email}/{$authToken}/{$item->id}.mp4\n\n";
        }

        return $m3u;
    }

    /**
     * Generate EPG XML
     */
    public function generateEPG(): string
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE tv SYSTEM "xmltv.dtd"><tv></tv>');
        $xml->addAttribute('generator-info-name', 'WatchTheFlix EPG Generator');
        $xml->addAttribute('generator-info-url', config('app.url'));

        // Add channels
        $channels = TvChannel::active()->get();
        foreach ($channels as $tvChannel) {
            $channel = $xml->addChild('channel');
            $channel->addAttribute('id', $tvChannel->slug);
            $displayName = $channel->addChild('display-name', htmlspecialchars($tvChannel->name));
            
            if ($tvChannel->logo_url) {
                $icon = $channel->addChild('icon');
                $icon->addAttribute('src', $tvChannel->logo_url);
            }
        }

        // Add programs
        $programs = TvProgram::with('channel')
            ->where('start_time', '>=', now()->subDays(1))
            ->where('end_time', '<=', now()->addDays(7))
            ->get();

        foreach ($programs as $tvProgram) {
            $programme = $xml->addChild('programme');
            $programme->addAttribute('channel', $tvProgram->channel->slug);
            $programme->addAttribute('start', $tvProgram->start_time->format('YmdHis O'));
            $programme->addAttribute('stop', $tvProgram->end_time->format('YmdHis O'));
            
            $title = $programme->addChild('title', htmlspecialchars($tvProgram->title));
            $title->addAttribute('lang', 'en');
            
            if ($tvProgram->description) {
                $desc = $programme->addChild('desc', htmlspecialchars($tvProgram->description));
                $desc->addAttribute('lang', 'en');
            }
            
            if ($tvProgram->genre) {
                $category = $programme->addChild('category', htmlspecialchars($tvProgram->genre));
                $category->addAttribute('lang', 'en');
            }

            if ($tvProgram->image_url) {
                $icon = $programme->addChild('icon');
                $icon->addAttribute('src', $tvProgram->image_url);
            }
        }

        return $xml->asXML();
    }
}
