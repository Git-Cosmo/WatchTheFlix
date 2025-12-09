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
}
