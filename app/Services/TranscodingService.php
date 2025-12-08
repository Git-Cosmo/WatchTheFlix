<?php

namespace App\Services;

use App\Models\Media;
use App\Models\TranscodingJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class TranscodingService
{
    private array $qualityProfiles = [
        '360p' => [
            'width' => 640,
            'height' => 360,
            'bitrate' => '500k',
            'audio_bitrate' => '64k',
        ],
        '480p' => [
            'width' => 854,
            'height' => 480,
            'bitrate' => '1000k',
            'audio_bitrate' => '96k',
        ],
        '720p' => [
            'width' => 1280,
            'height' => 720,
            'bitrate' => '2500k',
            'audio_bitrate' => '128k',
        ],
        '1080p' => [
            'width' => 1920,
            'height' => 1080,
            'bitrate' => '5000k',
            'audio_bitrate' => '192k',
        ],
        '4k' => [
            'width' => 3840,
            'height' => 2160,
            'bitrate' => '15000k',
            'audio_bitrate' => '256k',
        ],
    ];
    
    /**
     * Check if transcoding is enabled
     */
    public function isEnabled(): bool
    {
        return config('streaming.transcoding.enabled', false);
    }
    
    /**
     * Queue transcoding jobs for media
     */
    public function queueTranscoding(Media $media, array $qualities = null): array
    {
        if (!$this->isEnabled()) {
            throw new \Exception('Transcoding is not enabled');
        }
        
        $qualities = $qualities ?? array_keys($this->qualityProfiles);
        $jobs = [];
        
        foreach ($qualities as $quality) {
            if (!isset($this->qualityProfiles[$quality])) {
                continue;
            }
            
            // Check if job already exists
            $existingJob = TranscodingJob::where('media_id', $media->id)
                ->where('quality', $quality)
                ->whereIn('status', ['pending', 'processing'])
                ->first();
            
            if ($existingJob) {
                $jobs[] = $existingJob;
                continue;
            }
            
            $job = new TranscodingJob();
            $job->media_id = $media->id;
            $job->quality = $quality;
            $job->status = 'pending';
            $job->save();
            
            $jobs[] = $job;
        }
        
        return $jobs;
    }
    
    /**
     * Transcode media to specific quality
     */
    public function transcode(TranscodingJob $job): bool
    {
        $media = $job->media;
        $profile = $this->qualityProfiles[$job->quality];
        
        if (!$media->stream_url) {
            $job->status = 'failed';
            $job->error_message = 'No source stream URL available';
            $job->save();
            return false;
        }
        
        $job->status = 'processing';
        $job->save();
        
        try {
            $outputDir = storage_path('app/transcoded/' . $media->id . '/' . $job->quality);
            
            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            $outputPath = $outputDir . '/stream.m3u8';
            
            // Build FFmpeg command for HLS transcoding
            $command = $this->buildFFmpegCommand(
                $media->stream_url,
                $outputPath,
                $profile
            );
            
            Log::info('Starting transcode', [
                'job_id' => $job->id,
                'media_id' => $media->id,
                'quality' => $job->quality,
                'command' => $command,
            ]);
            
            // Execute FFmpeg
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(3600); // 1 hour timeout
            $process->run(function ($type, $buffer) use ($job) {
                // Parse progress from FFmpeg output
                if (preg_match('/time=(\d+):(\d+):(\d+)/', $buffer, $matches)) {
                    $seconds = ($matches[1] * 3600) + ($matches[2] * 60) + $matches[3];
                    $duration = $job->media->duration ?? 0;
                    
                    if ($duration > 0) {
                        $progress = min(100, (int)(($seconds / $duration) * 100));
                        $job->progress = $progress;
                        $job->save();
                    }
                }
            });
            
            if (!$process->isSuccessful()) {
                throw new \Exception($process->getErrorOutput());
            }
            
            // Update job status
            $job->status = 'completed';
            $job->progress = 100;
            $job->output_path = $outputPath;
            $job->save();
            
            // Update media available qualities
            $this->updateMediaQualities($media);
            
            Log::info('Transcode completed', [
                'job_id' => $job->id,
                'media_id' => $media->id,
                'quality' => $job->quality,
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Transcode failed', [
                'job_id' => $job->id,
                'media_id' => $media->id,
                'error' => $e->getMessage(),
            ]);
            
            $job->status = 'failed';
            $job->error_message = $e->getMessage();
            $job->save();
            
            return false;
        }
    }
    
    /**
     * Build FFmpeg command for HLS transcoding
     */
    private function buildFFmpegCommand(string $input, string $output, array $profile): string
    {
        $preset = config('streaming.transcoding.preset', 'medium');
        
        return sprintf(
            'ffmpeg -i "%s" ' .
            '-vf scale=%d:%d ' .
            '-c:v libx264 -preset %s -b:v %s -maxrate %s -bufsize %s ' .
            '-c:a aac -b:a %s ' .
            '-f hls -hls_time 4 -hls_playlist_type vod -hls_segment_filename "%s/segment_%%03d.ts" ' .
            '"%s"',
            $input,
            $profile['width'],
            $profile['height'],
            $preset,
            $profile['bitrate'],
            $profile['bitrate'],
            (int)filter_var($profile['bitrate'], FILTER_SANITIZE_NUMBER_INT) * 2 . 'k',
            $profile['audio_bitrate'],
            dirname($output),
            $output
        );
    }
    
    /**
     * Update media available qualities
     */
    private function updateMediaQualities(Media $media): void
    {
        $completedJobs = TranscodingJob::where('media_id', $media->id)
            ->where('status', 'completed')
            ->get();
        
        $qualities = $completedJobs->pluck('quality')->toArray();
        
        $media->available_qualities = $qualities;
        $media->transcoding_status = count($qualities) > 0 ? 'completed' : 'pending';
        $media->transcoded_at = now();
        
        // Set HLS playlist URL
        if (count($qualities) > 0) {
            $media->hls_playlist_url = url('/transcoded/' . $media->id . '/master.m3u8');
        }
        
        $media->save();
    }
    
    /**
     * Generate master HLS playlist
     */
    public function generateMasterPlaylist(Media $media): string
    {
        $qualities = $media->available_qualities ?? [];
        
        $playlist = "#EXTM3U\n#EXT-X-VERSION:3\n\n";
        
        foreach ($qualities as $quality) {
            if (!isset($this->qualityProfiles[$quality])) {
                continue;
            }
            
            $profile = $this->qualityProfiles[$quality];
            $bitrate = (int)filter_var($profile['bitrate'], FILTER_SANITIZE_NUMBER_INT) * 1000;
            
            $playlist .= sprintf(
                "#EXT-X-STREAM-INF:BANDWIDTH=%d,RESOLUTION=%dx%d\n%s/stream.m3u8\n\n",
                $bitrate,
                $profile['width'],
                $profile['height'],
                $quality
            );
        }
        
        // Save master playlist
        $masterPath = storage_path('app/transcoded/' . $media->id . '/master.m3u8');
        file_put_contents($masterPath, $playlist);
        
        return $masterPath;
    }
    
    /**
     * Get transcoding statistics
     */
    public function getStats(): array
    {
        return [
            'pending' => TranscodingJob::where('status', 'pending')->count(),
            'processing' => TranscodingJob::where('status', 'processing')->count(),
            'completed' => TranscodingJob::where('status', 'completed')->count(),
            'failed' => TranscodingJob::where('status', 'failed')->count(),
            'total_media_transcoded' => Media::whereNotNull('transcoded_at')->count(),
        ];
    }
    
    /**
     * Cleanup old transcode files
     */
    public function cleanup(int $daysOld = 30): int
    {
        $count = 0;
        $cutoffDate = now()->subDays($daysOld);
        
        // Delete failed jobs older than cutoff
        $failedJobs = TranscodingJob::where('status', 'failed')
            ->where('updated_at', '<', $cutoffDate)
            ->get();
        
        foreach ($failedJobs as $job) {
            $job->delete();
            $count++;
        }
        
        return $count;
    }
}
