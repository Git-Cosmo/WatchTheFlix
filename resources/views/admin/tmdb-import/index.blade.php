@extends('layouts.admin')

@section('title', 'TMDB Bulk Import')

@section('content')
<div class="max-w-7xl">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">TMDB Bulk Import</h1>
        <p class="text-dark-400 mt-2">Import popular movies and TV shows from The Movie Database</p>
    </div>

    @if(!app(\App\Services\TmdbService::class)->isConfigured())
    <!-- Warning: TMDB not configured -->
    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4 mb-8">
        <div class="flex items-start gap-3">
            <svg class="h-6 w-6 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-yellow-400 mb-1">TMDB API Key Not Configured</h3>
                <p class="text-dark-300 text-sm">
                    Please configure your TMDB API key in 
                    <a href="{{ route('admin.settings.index') }}" class="text-accent-400 hover:text-accent-300 underline">Settings</a> 
                    to use this feature. You can get a free API key from 
                    <a href="https://www.themoviedb.org/settings/api" target="_blank" class="text-accent-400 hover:text-accent-300 underline">TMDB</a>.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Bulk Import Section -->
    <div class="card p-6 mb-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-accent-500/10 rounded-lg flex items-center justify-center">
                <svg class="h-6 w-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold">Bulk Import Popular Content</h2>
                <p class="text-dark-400 text-sm">Import multiple movies or TV shows at once from TMDB</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.tmdb-import.bulk-import') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Content Type</label>
                    <select name="type" class="input-field w-full" required>
                        <option value="movie">Movies</option>
                        <option value="tv">TV Shows</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Category</label>
                    <select name="category" class="input-field w-full" required>
                        <option value="popular">Popular</option>
                        <option value="top_rated">Top Rated</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="now_playing">Now Playing</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Number of Items</label>
                    <input type="number" name="limit" min="1" max="100" value="20" class="input-field w-full" required>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Import Content
            </button>
        </form>
    </div>

    <!-- How It Works -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Features -->
        <div class="card p-6">
            <h3 class="font-semibold mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                What Gets Imported:
            </h3>
            <ul class="space-y-2 text-dark-300 text-sm">
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-accent-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Full metadata (title, description, genres, ratings)
                </li>
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-accent-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Poster and backdrop images
                </li>
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-accent-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Cast and crew information
                </li>
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-accent-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Streaming platform availability
                </li>
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-accent-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Production companies and details
                </li>
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-accent-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    SEO-optimized data and structured markup
                </li>
            </ul>
        </div>

        <!-- Important Notes -->
        <div class="card p-6">
            <h3 class="font-semibold mb-4 flex items-center gap-2">
                <svg class="h-5 w-5 text-accent-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                Important Notes:
            </h3>
            <ul class="space-y-2 text-dark-300 text-sm">
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    TMDB API key must be configured in Settings
                </li>
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Existing items will be automatically skipped
                </li>
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    All imported content is automatically published
                </li>
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Images are fetched from TMDB CDN (not stored locally)
                </li>
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    You can edit all imported content manually
                </li>
                <li class="flex items-start gap-2">
                    <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Large imports may take several minutes to complete
                </li>
            </ul>
        </div>
    </div>

    <!-- Quick Tips -->
    <div class="card p-6">
        <h3 class="font-semibold mb-3 flex items-center gap-2">
            <svg class="h-5 w-5 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            Quick Tips:
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-dark-300">
            <div class="flex items-start gap-2">
                <span class="text-accent-400 font-bold">1.</span>
                <p>Start with a small limit (10-20 items) to test the import process</p>
            </div>
            <div class="flex items-start gap-2">
                <span class="text-accent-400 font-bold">2.</span>
                <p>Use "Popular" category for most-watched content</p>
            </div>
            <div class="flex items-start gap-2">
                <span class="text-accent-400 font-bold">3.</span>
                <p>Review and customize imported content in the Media section</p>
            </div>
        </div>
    </div>
</div>
@endsection
