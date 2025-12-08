<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageOptimizationService
{
    /**
     * Thumbnail sizes configuration
     */
    protected array $thumbnailSizes = [
        'small' => ['width' => 300, 'height' => 450],   // Card view
        'medium' => ['width' => 500, 'height' => 750],  // Grid view
        'large' => ['width' => 800, 'height' => 1200],  // Detail view
    ];

    /**
     * Generate thumbnails for an image
     *
     * @param  string  $imagePath  Original image path
     * @param  string  $disk  Storage disk (default: 'public')
     * @return array Generated thumbnail paths
     */
    public function generateThumbnails(string $imagePath, string $disk = 'public'): array
    {
        $thumbnails = [];

        if (! Storage::disk($disk)->exists($imagePath)) {
            return $thumbnails;
        }

        $fullPath = Storage::disk($disk)->path($imagePath);
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'] ?? 'jpg';

        foreach ($this->thumbnailSizes as $size => $dimensions) {
            try {
                $thumbnailPath = "{$directory}/thumbnails/{$filename}_{$size}.{$extension}";
                $thumbnailFullPath = Storage::disk($disk)->path($thumbnailPath);

                // Create thumbnails directory if it doesn't exist
                $thumbnailDir = dirname($thumbnailFullPath);
                if (! file_exists($thumbnailDir)) {
                    mkdir($thumbnailDir, 0755, true);
                }

                // Generate thumbnail
                $image = Image::make($fullPath);
                $image->fit($dimensions['width'], $dimensions['height'], function ($constraint) {
                    $constraint->upsize();
                });

                // Optimize quality
                $image->save($thumbnailFullPath, 85);

                $thumbnails[$size] = $thumbnailPath;
            } catch (\Exception $e) {
                \Log::error("Failed to generate {$size} thumbnail for {$imagePath}: ".$e->getMessage());
            }
        }

        return $thumbnails;
    }

    /**
     * Get thumbnail URL or fallback to original
     */
    public function getThumbnailUrl(?string $imagePath, string $size = 'medium', string $disk = 'public'): ?string
    {
        if (! $imagePath) {
            return null;
        }

        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'] ?? 'jpg';

        $thumbnailPath = "{$directory}/thumbnails/{$filename}_{$size}.{$extension}";

        // Check if thumbnail exists
        if (Storage::disk($disk)->exists($thumbnailPath)) {
            return Storage::disk($disk)->url($thumbnailPath);
        }

        // Fallback to original image
        if (Storage::disk($disk)->exists($imagePath)) {
            return Storage::disk($disk)->url($imagePath);
        }

        return null;
    }

    /**
     * Delete thumbnails for an image
     */
    public function deleteThumbnails(string $imagePath, string $disk = 'public'): bool
    {
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'] ?? 'jpg';

        $deleted = true;

        foreach ($this->thumbnailSizes as $size => $dimensions) {
            $thumbnailPath = "{$directory}/thumbnails/{$filename}_{$size}.{$extension}";

            if (Storage::disk($disk)->exists($thumbnailPath)) {
                $deleted = $deleted && Storage::disk($disk)->delete($thumbnailPath);
            }
        }

        return $deleted;
    }

    /**
     * Optimize an existing image
     */
    public function optimizeImage(string $imagePath, string $disk = 'public', int $quality = 85): bool
    {
        try {
            if (! Storage::disk($disk)->exists($imagePath)) {
                return false;
            }

            $fullPath = Storage::disk($disk)->path($imagePath);
            $image = Image::make($fullPath);

            // Optimize and save
            $image->save($fullPath, $quality);

            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to optimize image {$imagePath}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Get lazy loading placeholder (base64 encoded low-quality image)
     */
    public function getLazyPlaceholder(string $imagePath, string $disk = 'public'): ?string
    {
        try {
            if (! Storage::disk($disk)->exists($imagePath)) {
                return null;
            }

            $fullPath = Storage::disk($disk)->path($imagePath);
            $image = Image::make($fullPath);

            // Create tiny placeholder (20x30 for typical poster ratio)
            $image->fit(20, 30);
            $image->blur(5);

            // Convert to base64
            return 'data:image/jpeg;base64,'.base64_encode($image->encode('jpg', 50));
        } catch (\Exception $e) {
            return null;
        }
    }
}
