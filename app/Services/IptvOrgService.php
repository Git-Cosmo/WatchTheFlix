<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * IPTV-ORG API Service
 *
 * This service provides methods to interact with iptv-org/api for fetching
 * TV channel metadata including:
 * - Channel names and IDs
 * - Country information
 * - Categories
 * - Network and owner information
 * - Website URLs
 * - Launch dates and status
 */
class IptvOrgService
{
    protected $apiUrl = 'https://iptv-org.github.io/api';

    /**
     * Get all channels from IPTV-ORG API
     */
    public function getAllChannels(): ?array
    {
        try {
            $response = Http::timeout(30)->get("{$this->apiUrl}/channels.json");

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('IPTV-ORG get channels failed: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get channels filtered by country
     */
    public function getChannelsByCountry(string $countryCode): ?array
    {
        $allChannels = $this->getAllChannels();

        if (! $allChannels) {
            return null;
        }

        return array_filter($allChannels, function ($channel) use ($countryCode) {
            return isset($channel['country']) && strtoupper($channel['country']) === strtoupper($countryCode);
        });
    }

    /**
     * Get channels filtered by multiple countries
     */
    public function getChannelsByCountries(array $countryCodes): ?array
    {
        $allChannels = $this->getAllChannels();

        if (! $allChannels) {
            return null;
        }

        $countryCodes = array_map('strtoupper', $countryCodes);

        return array_filter($allChannels, function ($channel) use ($countryCodes) {
            return isset($channel['country']) && in_array(strtoupper($channel['country']), $countryCodes);
        });
    }

    /**
     * Get available EPG guides
     */
    public function getGuides(): ?array
    {
        try {
            $response = Http::timeout(30)->get("{$this->apiUrl}/guides.json");

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('IPTV-ORG get guides failed: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get available streams
     */
    public function getStreams(): ?array
    {
        try {
            $response = Http::timeout(30)->get("{$this->apiUrl}/streams.json");

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('IPTV-ORG get streams failed: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Search channels by name
     */
    public function searchChannels(string $query): ?array
    {
        $allChannels = $this->getAllChannels();

        if (! $allChannels) {
            return null;
        }

        $query = strtolower($query);

        return array_filter($allChannels, function ($channel) use ($query) {
            $name = strtolower($channel['name'] ?? '');
            $altNames = array_map('strtolower', $channel['alt_names'] ?? []);

            return str_contains($name, $query) || in_array(true, array_map(fn ($alt) => str_contains($alt, $query), $altNames));
        });
    }

    /**
     * Test the API connection
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::timeout(10)->get("{$this->apiUrl}/channels.json");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('IPTV-ORG connection test failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Get channel by ID
     */
    public function getChannelById(string $id): ?array
    {
        $allChannels = $this->getAllChannels();

        if (! $allChannels) {
            return null;
        }

        foreach ($allChannels as $channel) {
            if ($channel['id'] === $id) {
                return $channel;
            }
        }

        return null;
    }

    /**
     * Get popular US channels with filtering criteria
     * Filters by:
     * - Major categories (news, sports, entertainment, general)
     * - Non-NSFW content
     * - Active channels (not closed)
     * - Has network or owner information (indicates mainstream channel)
     */
    public function getPopularUSChannels(): ?array
    {
        $usChannels = $this->getChannelsByCountry('US');

        if (! $usChannels) {
            return null;
        }

        $popularCategories = ['news', 'sports', 'entertainment', 'general', 'business', 'documentary'];

        return array_filter($usChannels, function ($channel) use ($popularCategories) {
            // Filter out NSFW content
            if (! empty($channel['is_nsfw'])) {
                return false;
            }

            // Filter out closed channels
            if (! empty($channel['closed'])) {
                return false;
            }

            // Include channels with popular categories
            if (! empty($channel['categories'])) {
                $hasPopularCategory = ! empty(array_intersect($channel['categories'], $popularCategories));
                if ($hasPopularCategory) {
                    return true;
                }
            }

            // Include major network channels (has network or owners)
            if (! empty($channel['network']) || ! empty($channel['owners'])) {
                return true;
            }

            return false;
        });
    }

    /**
     * Get EPG guides for a specific channel
     */
    public function getGuidesForChannel(string $channelId): ?array
    {
        $guides = $this->getGuides();

        if (! $guides) {
            return null;
        }

        return array_filter($guides, function ($guide) use ($channelId) {
            return isset($guide['channel']) && $guide['channel'] === $channelId;
        });
    }

    /**
     * Get streams for a specific channel
     */
    public function getStreamsForChannel(string $channelId): ?array
    {
        $streams = $this->getStreams();

        if (! $streams) {
            return null;
        }

        return array_filter($streams, function ($stream) use ($channelId) {
            return isset($stream['channel']) && $stream['channel'] === $channelId;
        });
    }

    /**
     * Get guides by country
     */
    public function getGuidesByCountry(string $countryCode): ?array
    {
        $guides = $this->getGuides();
        $channels = $this->getChannelsByCountry($countryCode);

        if (! $guides || ! $channels) {
            return null;
        }

        $channelIds = array_column($channels, 'id');

        return array_filter($guides, function ($guide) use ($channelIds) {
            return isset($guide['channel']) && in_array($guide['channel'], $channelIds);
        });
    }

    /**
     * Fetch EPG XML data from a guide URL
     */
    public function fetchEpgXml(string $url): ?string
    {
        try {
            $response = Http::timeout(60)->get($url);

            return $response->successful() ? $response->body() : null;
        } catch (\Exception $e) {
            Log::error('IPTV-ORG EPG fetch failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
