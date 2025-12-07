@extends('layouts.app')

@section('title', 'Browse Media')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">Browse Content</h1>
        <div class="text-sm text-gh-text-muted">
            {{ $media->total() }} {{ $media->total() === 1 ? 'item' : 'items' }} found
        </div>
    </div>

    <!-- Enhanced Search and Filters -->
    <div class="mb-8 card p-6">
        <form method="GET" action="{{ route('media.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gh-text-muted mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search titles..." class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gh-text-muted mb-2">Type</label>
                    <select name="type" class="input-field w-full">
                        <option value="">All Types</option>
                        <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>Movies</option>
                        <option value="series" {{ request('type') == 'series' ? 'selected' : '' }}>Series</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gh-text-muted mb-2">Genre</label>
                    <select name="genre" class="input-field w-full">
                        <option value="">All Genres</option>
                        @foreach($allGenres as $genre)
                        <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gh-text-muted mb-2">Sort By</label>
                    <select name="sort" class="input-field w-full">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Added</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Release Year</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary">
                    <svg class="h-4 w-4 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'type', 'genre', 'sort']))
                <a href="{{ route('media.index') }}" class="btn-secondary">
                    <svg class="h-4 w-4 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear Filters
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($media as $item)
        <a href="{{ route('media.show', $item) }}" class="card overflow-hidden hover:border-accent-500 transition">
            @if($item->poster_url)
            <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-64 object-cover">
            @else
            <div class="w-full h-64 bg-dark-800 flex items-center justify-center">
                <span class="text-dark-500 text-4xl">🎬</span>
            </div>
            @endif
            <div class="p-3">
                <h3 class="text-sm font-semibold truncate">{{ $item->title }}</h3>
                <p class="text-xs text-dark-400">{{ $item->release_year }}</p>
                @if($item->imdb_rating)
                <p class="text-xs text-accent-500 mt-1">⭐ {{ $item->imdb_rating }}</p>
                @endif
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-dark-400">No content found.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $media->links() }}
    </div>
</div>
@endsection
