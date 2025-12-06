<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RealDebridService
{
    protected $apiUrl;

    protected $token;

    public function __construct(?string $token = null)
    {
        $this->apiUrl = config('services.real_debrid.api_url', 'https://api.real-debrid.com/rest/1.0');
        $this->token = $token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function validateToken(): bool
    {
        try {
            $response = $this->makeRequest('GET', '/user');

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Real-Debrid token validation failed: '.$e->getMessage());

            return false;
        }
    }

    public function getUserInfo(): ?array
    {
        try {
            $response = $this->makeRequest('GET', '/user');

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to get Real-Debrid user info: '.$e->getMessage());

            return null;
        }
    }

    public function unrestrictLink(string $link): ?array
    {
        try {
            $response = $this->makeRequest('POST', '/unrestrict/link', [
                'link' => $link,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to unrestrict link: '.$e->getMessage());

            return null;
        }
    }

    public function getTorrents(): ?array
    {
        try {
            $response = $this->makeRequest('GET', '/torrents');

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to get torrents: '.$e->getMessage());

            return null;
        }
    }

    public function addMagnet(string $magnet): ?array
    {
        try {
            $response = $this->makeRequest('POST', '/torrents/addMagnet', [
                'magnet' => $magnet,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to add magnet: '.$e->getMessage());

            return null;
        }
    }

    public function selectFiles(string $torrentId, string $files = 'all'): ?array
    {
        try {
            $response = $this->makeRequest('POST', "/torrents/selectFiles/{$torrentId}", [
                'files' => $files,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to select files: '.$e->getMessage());

            return null;
        }
    }

    public function getTorrentInfo(string $torrentId): ?array
    {
        try {
            $response = $this->makeRequest('GET', "/torrents/info/{$torrentId}");

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Failed to get torrent info: '.$e->getMessage());

            return null;
        }
    }

    protected function makeRequest(string $method, string $endpoint, array $data = [])
    {
        if (! $this->token) {
            throw new \Exception('Real-Debrid token not set');
        }

        $url = $this->apiUrl.$endpoint;

        return Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->{strtolower($method)}($url, $data);
    }
}
