<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EpgService
{
    /**
     * Fetch EPG data from external provider
     */
    public function fetchEpgData(string $url): array
    {
        try {
            $response = Http::timeout(60)->get($url);

            if (! $response->successful()) {
                Log::error('EPG fetch failed', ['status' => $response->status()]);

                return [];
            }

            $xmlContent = $response->body();

            return $this->parseXmltvData($xmlContent);
        } catch (\Exception $e) {
            Log::error('EPG fetch error', ['error' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * Parse XMLTV format EPG data
     */
    protected function parseXmltvData(string $xmlContent): array
    {
        try {
            $xml = simplexml_load_string($xmlContent);

            if ($xml === false) {
                Log::error('Failed to parse XML');

                return [];
            }

            $programs = [];

            // Parse channels first to create a map
            $channelMap = [];
            if (isset($xml->channel)) {
                foreach ($xml->channel as $channel) {
                    $channelId = (string) $channel['id'];
                    $channelName = (string) $channel->{'display-name'}[0];
                    $channelMap[$channelId] = $channelName;
                }
            }

            // Parse programs
            if (isset($xml->programme)) {
                foreach ($xml->programme as $programme) {
                    $channelId = (string) $programme['channel'];
                    $startTime = $this->parseXmltvTime((string) $programme['start']);
                    $endTime = $this->parseXmltvTime((string) $programme['stop']);

                    // Skip if times are invalid
                    if (! $startTime || ! $endTime) {
                        continue;
                    }

                    $programs[] = [
                        'channel_id' => $channelId,
                        'channel_name' => $channelMap[$channelId] ?? $channelId,
                        'title' => (string) $programme->title,
                        'description' => isset($programme->desc) ? (string) $programme->desc : null,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'genre' => isset($programme->category) ? (string) $programme->category : null,
                        'image_url' => isset($programme->icon) ? (string) $programme->icon['src'] : null,
                    ];
                }
            }

            return $programs;
        } catch (\Exception $e) {
            Log::error('XML parsing error', ['error' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * Parse XMLTV datetime format (YYYYMMDDHHmmss +ZZZZ)
     */
    protected function parseXmltvTime(string $time): ?Carbon
    {
        try {
            // Format: 20241208120000 +0000
            $dateTime = substr($time, 0, 14); // Extract YYYYMMDDHHmmss
            $timezone = trim(substr($time, 14)); // Extract timezone

            $parsed = Carbon::createFromFormat('YmdHis', $dateTime, 'UTC');

            // Apply timezone offset if present
            if ($timezone && strlen($timezone) >= 5) {
                $sign = $timezone[0];
                $hours = (int) substr($timezone, 1, 2);
                $minutes = (int) substr($timezone, 3, 2);

                $offsetMinutes = ($hours * 60) + $minutes;
                if ($sign === '-') {
                    $offsetMinutes *= -1;
                }

                $parsed->addMinutes($offsetMinutes);
            }

            return $parsed;
        } catch (\Exception $e) {
            Log::warning('Failed to parse time', ['time' => $time, 'error' => $e->getMessage()]);

            return null;
        }
    }
}
