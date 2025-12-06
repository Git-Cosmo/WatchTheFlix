@extends('layouts.app')

@section('title', 'Browse Media')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Browse Content</h1>

    <!-- Search and Filters -->
    <div class="mb-8 card p-6">
        <form method="GET" action="{{ route('media.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search titles..." class="input-field w-full">
            </div>
            <div>
                <select name="type" class="input-field w-full">
                    <option value="">All Types</option>
                    <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>Movies</option>
                    <option value="series" {{ request('type') == 'series' ? 'selected' : '' }}>Series</option>
                </select>
            </div>
            <div class="col-span-2">
                <button type="submit" class="btn-primary w-full">Search</button>
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
