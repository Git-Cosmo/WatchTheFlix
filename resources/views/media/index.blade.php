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
                    <label class="block text-sm font-medium text-gh-text-muted mb-2">Platform</label>
                    <select name="platform" class="input-field w-full">
                        <option value="">All Platforms</option>
                        @foreach($platforms as $platform)
                        <option value="{{ $platform->id }}" {{ request('platform') == $platform->id ? 'selected' : '' }}>{{ $platform->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Advanced Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gh-text-muted mb-2">Year From</label>
                    <input type="number" name="year_from" value="{{ request('year_from') }}" 
                           min="{{ $yearRange['min'] }}" max="{{ $yearRange['max'] }}" 
                           placeholder="{{ $yearRange['min'] }}" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gh-text-muted mb-2">Year To</label>
                    <input type="number" name="year_to" value="{{ request('year_to') }}" 
                           min="{{ $yearRange['min'] }}" max="{{ $yearRange['max'] }}" 
                           placeholder="{{ $yearRange['max'] }}" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gh-text-muted mb-2">Min Rating</label>
                    <select name="min_rating" class="input-field w-full">
                        <option value="">Any Rating</option>
                        <option value="7.0" {{ request('min_rating') == '7.0' ? 'selected' : '' }}>7.0+</option>
                        <option value="7.5" {{ request('min_rating') == '7.5' ? 'selected' : '' }}>7.5+</option>
                        <option value="8.0" {{ request('min_rating') == '8.0' ? 'selected' : '' }}>8.0+</option>
                        <option value="8.5" {{ request('min_rating') == '8.5' ? 'selected' : '' }}>8.5+</option>
                        <option value="9.0" {{ request('min_rating') == '9.0' ? 'selected' : '' }}>9.0+</option>
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
                @if(request()->hasAny(['search', 'type', 'genre', 'sort', 'platform', 'year_from', 'year_to', 'min_rating']))
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
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">
        @forelse($media as $item)
        <a href="{{ $item->getRouteUrl() }}" class="group">
            <div class="relative overflow-hidden rounded-xl bg-gh-bg-secondary border border-gh-border hover:border-accent-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-accent-500/20 hover:-translate-y-1">
                <!-- Poster -->
                <div class="relative aspect-[2/3] overflow-hidden">
                    @if($item->poster_url)
                    <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-gh-bg-tertiary to-gh-bg flex items-center justify-center">
                        <svg class="h-16 w-16 text-gh-text-muted opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                        </svg>
                    </div>
                    @endif
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <!-- Rating Badge -->
                    @if($item->imdb_rating)
                    <div class="absolute top-3 right-3 flex items-center gap-1.5 px-2.5 py-1.5 bg-black/80 backdrop-blur-md rounded-lg border border-white/10">
                        <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-sm font-bold text-white">{{ number_format($item->imdb_rating, 1) }}</span>
                    </div>
                    @endif

                    <!-- Type Badge -->
                    <div class="absolute top-3 left-3 px-2.5 py-1 bg-accent-600/90 backdrop-blur-md rounded-md">
                        <span class="text-xs font-bold text-white uppercase tracking-wider">{{ $item->type }}</span>
                    </div>
                    
                    <!-- Play Icon (on hover) -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <div class="w-16 h-16 bg-accent-600 rounded-full flex items-center justify-center transform scale-75 group-hover:scale-100 transition-transform duration-300 shadow-2xl">
                            <svg class="h-8 w-8 text-white ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Info Section -->
                <div class="p-4 space-y-2">
                    <h3 class="text-sm font-bold text-white group-hover:text-accent-400 transition-colors line-clamp-2 leading-tight">{{ $item->title }}</h3>
                    <div class="flex items-center justify-between text-xs text-gh-text-muted">
                        <span>{{ $item->release_year }}</span>
                        @if($item->runtime)
                        <span>{{ $item->runtime }} min</span>
                        @endif
                    </div>
                    @if($item->genres && count($item->genres) > 0)
                    <div class="flex flex-wrap gap-1 pt-1">
                        @foreach(array_slice($item->genres, 0, 2) as $genre)
                        <span class="text-xs px-2 py-0.5 bg-gh-bg-tertiary rounded-full text-gh-text-muted">{{ $genre }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full text-center py-20">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gh-bg-secondary rounded-full mb-6">
                <svg class="h-10 w-10 text-gh-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
            </div>
            <p class="text-gh-text-muted text-lg mb-2">No content found</p>
            <p class="text-gh-text-muted text-sm">Try adjusting your filters or search query</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $media->links() }}
    </div>
</div>
@endsection
