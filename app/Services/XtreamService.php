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
 * Provides Xtream Codes-compatible API functionality for IPTV applications
 */
class XtreamService
{
    /**
     * Authenticate user and return credentials
     */
    public function authenticate(string $username, string $password): ?array
    {
        $user = User::where('email', $username)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        // Generate or retrieve auth token
        $token = $user->createToken('xtream-api')->plainTextToken;

        return [
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
    }

    /**
     * Get live streams (TV channels)
     */
    public function getLiveStreams(?string $category = null): array
    {
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
                'tv_archive' => 0,
                'direct_source' => '',
                'tv_archive_duration' => 0,
            ];
        })->toArray();
    }

    /**
     * Get live stream categories
     */
    public function getLiveCategories(): array
    {
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
    }

    /**
     * Get VOD streams (movies/series)
     */
    public function getVodStreams(?string $category = null): array
    {
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
    }

    /**
     * Get VOD categories
     */
    public function getVodCategories(): array
    {
        return [
            [
                'category_id' => 'movie',
                'category_name' => 'Movies',
                'parent_id' => 0
            ],
            [
                'category_id' => 'series',
                'category_name' => 'TV Series',
                'parent_id' => 0
            ]
        ];
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
     * Get short EPG for live streams (Xtream format)
     */
    public function getShortEpg(int $streamId, ?int $limit = null): array
    {
        $channel = TvChannel::find($streamId);

        if (!$channel) {
            return [];
        }

        $query = TvProgram::where('tv_channel_id', $streamId)
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
