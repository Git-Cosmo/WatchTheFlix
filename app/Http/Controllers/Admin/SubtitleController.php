<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\SubtitleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubtitleController extends Controller
{
    protected $subtitleService;

    public function __construct(SubtitleService $subtitleService)
    {
        $this->subtitleService = $subtitleService;
    }

    public function index(Media $media)
    {
        return view('admin.media.subtitles', compact('media'));
    }

    public function store(Request $request, Media $media)
    {
        $request->validate([
            'subtitle_file' => 'required|file|mimes:srt,vtt|max:5120',
            'language' => 'required|string|max:10',
            'language_name' => 'required|string|max:50',
        ]);

        $file = $request->file('subtitle_file');

        // Validate subtitle format
        if (! $this->subtitleService->validateSubtitle($file)) {
            return back()->with('error', 'Invalid subtitle file format. Please upload a valid SRT or VTT file.');
        }

        // Store subtitle file
        $url = $this->subtitleService->storeSubtitle($file, $media->id, $request->language);

        // Update media subtitles
        $subtitles = $media->subtitles ?? [];
        $subtitles[$request->language] = [
            'url' => $url,
            'language' => $request->language,
            'language_name' => $request->language_name,
        ];

        $media->update(['subtitles' => $subtitles]);

        return back()->with('success', 'Subtitle added successfully.');
    }

    public function destroy(Media $media, string $language)
    {
        $subtitles = $media->subtitles ?? [];

        if (isset($subtitles[$language])) {
            // Delete file from storage
            $url = $subtitles[$language]['url'];
            $path = str_replace('/storage/', '', $url);
            Storage::disk('public')->delete($path);

            // Remove from media subtitles
            unset($subtitles[$language]);
            $media->update(['subtitles' => $subtitles]);

            return back()->with('success', 'Subtitle deleted successfully.');
        }

        return back()->with('error', 'Subtitle not found.');
    }
}
