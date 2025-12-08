<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SubtitleService
{
    /**
     * Parse SRT subtitle file
     */
    public function parseSrt(string $content): array
    {
        $subtitles = [];
        $blocks = preg_split('/\n\s*\n/', trim($content));

        foreach ($blocks as $block) {
            $lines = explode("\n", trim($block));

            if (count($lines) < 3) {
                continue;
            }

            // Line 0: Index
            // Line 1: Timecode
            // Line 2+: Text

            $timecode = $lines[1];
            $text = implode("\n", array_slice($lines, 2));

            if (preg_match('/(\d{2}:\d{2}:\d{2},\d{3})\s*-->\s*(\d{2}:\d{2}:\d{2},\d{3})/', $timecode, $matches)) {
                $subtitles[] = [
                    'start' => $this->srtTimeToSeconds($matches[1]),
                    'end' => $this->srtTimeToSeconds($matches[2]),
                    'text' => $text,
                ];
            }
        }

        return $subtitles;
    }

    /**
     * Parse VTT subtitle file
     */
    public function parseVtt(string $content): array
    {
        $subtitles = [];

        // Remove WEBVTT header
        $content = preg_replace('/^WEBVTT.*?\n\n/s', '', $content);

        $blocks = preg_split('/\n\s*\n/', trim($content));

        foreach ($blocks as $block) {
            $lines = explode("\n", trim($block));

            if (count($lines) < 2) {
                continue;
            }

            // First line might be an ID or timecode
            $timecodeIndex = 0;
            if (! preg_match('/-->/', $lines[0])) {
                $timecodeIndex = 1;
            }

            if (! isset($lines[$timecodeIndex])) {
                continue;
            }

            $timecode = $lines[$timecodeIndex];
            $text = implode("\n", array_slice($lines, $timecodeIndex + 1));

            if (preg_match('/(\d{2}:\d{2}:\d{2}\.\d{3})\s*-->\s*(\d{2}:\d{2}:\d{2}\.\d{3})/', $timecode, $matches)) {
                $subtitles[] = [
                    'start' => $this->vttTimeToSeconds($matches[1]),
                    'end' => $this->vttTimeToSeconds($matches[2]),
                    'text' => $text,
                ];
            }
        }

        return $subtitles;
    }

    /**
     * Convert SRT time format to seconds
     */
    protected function srtTimeToSeconds(string $time): float
    {
        // Format: 00:00:00,000
        $time = str_replace(',', '.', $time);
        [$hours, $minutes, $seconds] = explode(':', $time);

        return ((float) $hours * 3600) + ((float) $minutes * 60) + (float) $seconds;
    }

    /**
     * Convert VTT time format to seconds
     */
    protected function vttTimeToSeconds(string $time): float
    {
        // Format: 00:00:00.000
        [$hours, $minutes, $seconds] = explode(':', $time);

        return ((float) $hours * 3600) + ((float) $minutes * 60) + (float) $seconds;
    }

    /**
     * Store subtitle file
     */
    public function storeSubtitle($file, int $mediaId, string $language = 'en'): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "media_{$mediaId}_{$language}.".$extension;
        $path = $file->storeAs('subtitles', $filename, 'public');

        return Storage::url($path);
    }

    /**
     * Validate subtitle file format
     */
    public function validateSubtitle($file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, ['srt', 'vtt'])) {
            return false;
        }

        $content = file_get_contents($file->getRealPath());

        if ($extension === 'srt') {
            return $this->validateSrt($content);
        } elseif ($extension === 'vtt') {
            return $this->validateVtt($content);
        }

        return false;
    }

    /**
     * Validate SRT format
     */
    protected function validateSrt(string $content): bool
    {
        return preg_match('/\d+\n\d{2}:\d{2}:\d{2},\d{3}\s*-->\s*\d{2}:\d{2}:\d{2},\d{3}/', $content) === 1;
    }

    /**
     * Validate VTT format
     */
    protected function validateVtt(string $content): bool
    {
        return str_starts_with($content, 'WEBVTT') &&
            preg_match('/\d{2}:\d{2}:\d{2}\.\d{3}\s*-->\s*\d{2}:\d{2}:\d{2}\.\d{3}/', $content) === 1;
    }
}
