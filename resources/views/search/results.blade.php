@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-4">
        <span class="bg-gradient-to-r from-accent-400 to-accent-600 bg-clip-text text-transparent">
            Search Results
        </span>
    </h1>
    <p class="text-dark-400 mb-8">
        Showing results for "<span class="text-white font-semibold">{{ $query }}</span>"
        <span class="text-accent-400 ml-2">({{ number_format($totalResults) }} total results)</span>
    </p>
    
    <!-- Search Form -->
    <div class="card p-6 mb-8 border-accent-700/30">
        <form action="{{ route('search.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <input 
                type="text" 
                name="q" 
                placeholder="Search movies, TV shows, channels, forums..." 
                class="flex-1 input-field"
                value="{{ $query }}"
                required
            >
            <select name="type" class="input-field md:w-48">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Results</option>
                <option value="media" {{ $type === 'media' ? 'selected' : '' }}>Movies & TV Shows</option>
                <option value="channels" {{ $type === 'channels' ? 'selected' : '' }}>TV Channels</option>
                <option value="programs" {{ $type === 'programs' ? 'selected' : '' }}>TV Programs</option>
                <option value="forum" {{ $type === 'forum' ? 'selected' : '' }}>Forum Threads</option>
            </select>
            <button type="submit" class="btn-primary px-8 whitespace-nowrap">
                <svg class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Search
            </button>
        </form>
    </div>
    
    @if($totalResults === 0)
        <div class="card p-16 text-center">
            <div class="text-8xl mb-6">🔍</div>
            <h2 class="text-2xl font-bold mb-3">No Results Found</h2>
            <p class="text-dark-400 text-lg">
                We couldn't find anything matching "{{ $query }}". Try different keywords or browse our content.
            </p>
            <div class="flex justify-center gap-4 mt-6">
                <a href="{{ route('movies.index') }}" class="btn-secondary">Browse Movies</a>
                <a href="{{ route('tv-shows.index') }}" class="btn-secondary">Browse TV Shows</a>
                <a href="{{ route('tv-guide.index') }}" class="btn-secondary">TV Guide</a>
            </div>
        </div>
    @else
        <!-- Media Results -->
        @if(isset($results['media']) && $results['media']->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <svg class="h-6 w-6 mr-2 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                Movies & TV Shows
                <span class="text-sm text-dark-400 font-normal ml-2">({{ $results['media']->total() }})</span>
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($results['media'] as $media)
                    <a href="{{ $media->getRouteUrl() }}" class="group">
                        <div class="card overflow-hidden hover:border-accent-500/50 transition-all duration-300 h-full">
                            @if($media->poster_url)
                                <img src="{{ $media->poster_url }}" alt="{{ $media->title }}" class="w-full h-80 object-cover group-hover:opacity-90 transition-opacity">
                            @else
                                <div class="w-full h-80 bg-dark-800 flex items-center justify-center">
                                    <span class="text-6xl">🎬</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-1 group-hover:text-accent-400 transition-colors line-clamp-2">{{ $media->title }}</h3>
                                <p class="text-sm text-dark-400">{{ $media->release_year }} • {{ ucfirst($media->type) }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($results['media']->hasPages())
                <div class="mt-6">{{ $results['media']->appends(['q' => $query, 'type' => $type])->links() }}</div>
            @endif
        </div>
        @endif
        
        <!-- TV Channels Results -->
        @if(isset($results['channels']) && $results['channels']->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <svg class="h-6 w-6 mr-2 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                TV Channels
                <span class="text-sm text-dark-400 font-normal ml-2">({{ $results['channels']->total() }})</span>
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($results['channels'] as $channel)
                    <a href="{{ route('tv-guide.channel', $channel) }}" class="group">
                        <div class="card p-6 hover:border-accent-500/50 transition-all duration-300">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xl font-bold group-hover:text-accent-400 transition-colors">{{ $channel->name }}</h3>
                                @if($channel->channel_number)
                                    <span class="text-sm text-dark-400 bg-dark-700 px-3 py-1 rounded-full">Ch. {{ $channel->channel_number }}</span>
                                @endif
                            </div>
                            @if($channel->description)
                                <p class="text-dark-400 text-sm line-clamp-2">{{ $channel->description }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            @if($results['channels']->hasPages())
                <div class="mt-6">{{ $results['channels']->appends(['q' => $query, 'type' => $type])->links() }}</div>
            @endif
        </div>
        @endif
        
        <!-- TV Programs Results -->
        @if(isset($results['programs']) && $results['programs']->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <svg class="h-6 w-6 mr-2 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                TV Programs
                <span class="text-sm text-dark-400 font-normal ml-2">({{ $results['programs']->total() }})</span>
            </h2>
            <div class="space-y-4">
                @foreach($results['programs'] as $program)
                    <a href="{{ route('tv-guide.channel', $program->channel) }}" class="block group">
                        <div class="card p-6 hover:border-accent-500/50 transition-all duration-300">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold mb-2 group-hover:text-accent-400 transition-colors">{{ $program->title }}</h3>
                                    <p class="text-sm text-accent-400 mb-2">{{ $program->channel->name }}</p>
                                    <p class="text-sm text-dark-400">{{ $program->start_time->format('M j, Y g:i A') }}</p>
                                    @if($program->description)
                                        <p class="text-dark-400 text-sm mt-2 line-clamp-2">{{ $program->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($results['programs']->hasPages())
                <div class="mt-6">{{ $results['programs']->appends(['q' => $query, 'type' => $type])->links() }}</div>
            @endif
        </div>
        @endif
        
        <!-- Forum Threads Results -->
        @if(isset($results['forum']) && $results['forum']->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <svg class="h-6 w-6 mr-2 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                Forum Threads
                <span class="text-sm text-dark-400 font-normal ml-2">({{ $results['forum']->total() }})</span>
            </h2>
            <div class="space-y-4">
                @foreach($results['forum'] as $thread)
                    <a href="{{ route('forum.thread.show', $thread) }}" class="block group">
                        <div class="card p-6 hover:border-accent-500/50 transition-all duration-300">
                            <h3 class="text-xl font-bold mb-2 group-hover:text-accent-400 transition-colors">{{ $thread->title }}</h3>
                            <p class="text-sm text-dark-400 mb-2">
                                in <span class="text-accent-400">{{ $thread->category->name }}</span> 
                                by <span class="text-white">{{ $thread->user->name }}</span>
                            </p>
                            <p class="text-dark-400 text-sm line-clamp-2">{{ strip_tags($thread->body) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($results['forum']->hasPages())
                <div class="mt-6">{{ $results['forum']->appends(['q' => $query, 'type' => $type])->links() }}</div>
            @endif
        </div>
        @endif
    @endif
</div>
@endsection
