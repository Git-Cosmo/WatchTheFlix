@extends('layouts.admin')

@section('title', 'TMDB Bulk Import')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">TMDB Bulk Import</h1>

    <!-- Bulk Import Section -->
    <div class="card p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Bulk Import Popular Content</h2>
        <p class="text-dark-300 mb-6">Quickly import popular movies or TV shows from TMDB.</p>

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

    <!-- Instructions -->
    <div class="mt-8 bg-dark-800 border border-dark-700 rounded-lg p-6">
        <h3 class="font-semibold mb-3 flex items-center gap-2">
            <svg class="h-5 w-5 text-accent-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            Important Notes:
        </h3>
        <ul class="list-disc list-inside text-dark-300 text-sm space-y-1">
            <li>TMDB API key must be configured in Settings</li>
            <li>Bulk import will skip items that already exist in the database</li>
            <li>Imported content will be automatically published</li>
            <li>Images will be fetched from TMDB and stored as URLs</li>
            <li>You can manually edit imported content after import</li>
        </ul>
    </div>
</div>
@endsection
