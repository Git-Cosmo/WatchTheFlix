@extends('layouts.app')

@section('title', 'Search Results - TV Guide')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('tv-guide.index') }}" class="inline-flex items-center text-accent-400 hover:text-accent-300 transition-colors font-medium">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to TV Guide
        </a>
    </div>
    
    <h1 class="text-4xl font-bold mb-4">
        <span class="bg-gradient-to-r from-accent-400 to-accent-600 bg-clip-text text-transparent">
            Search Results
        </span>
    </h1>
    <p class="text-dark-400 mb-8">
        Showing results for "<span class="text-white font-semibold">{{ $query }}</span>"
        @if($country)
            in <span class="text-white font-semibold">{{ strtoupper($country) }}</span>
        @endif
    </p>
    
    <!-- Search Form -->
    <div class="card p-6 mb-8 border-accent-700/30">
        <form action="{{ route('tv-guide.search') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <input 
                type="text" 
                name="query" 
                placeholder="Search for a program..." 
                class="flex-1 input-field"
                value="{{ $query }}"
                required
            >
            <select name="country" class="input-field md:w-48">
                <option value="">All Countries</option>
                <option value="uk" {{ $country == 'uk' ? 'selected' : '' }}>UK</option>
                <option value="us" {{ $country == 'us' ? 'selected' : '' }}>US</option>
            </select>
            <button type="submit" class="btn-primary px-8 whitespace-nowrap">
                <svg class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Search
            </button>
        </form>
    </div>
    
    @if($programs->isEmpty())
        <div class="card p-16 text-center">
            <div class="text-8xl mb-6">🔍</div>
            <h2 class="text-2xl font-bold mb-3">No Programs Found</h2>
            <p class="text-dark-400 text-lg">
                We couldn't find any TV programs matching "{{ $query }}".
                @if($country)
                    Try searching in all countries or use different keywords.
                @else
                    Try different keywords.
                @endif
            </p>
        </div>
    @else
        <div class="mb-4 text-dark-400">
            Found {{ $programs->total() }} program{{ $programs->total() !== 1 ? 's' : '' }}
        </div>
        
        <div class="space-y-4 mb-8">
            @foreach($programs as $program)
                <a href="{{ route('tv-guide.channel', $program->channel) }}" class="block group">
                    <div class="card p-6 hover:border-accent-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-accent-500/5">
                        <div class="flex items-start justify-between gap-6">
                            <div class="flex-1">
                                <!-- Channel Info -->
                                <div class="flex items-center gap-3 mb-3">
                                    @if($program->channel->logo_url)
                                        <img src="{{ $program->channel->logo_url }}" alt="{{ $program->channel->name }}" class="w-8 h-8 object-contain rounded">
                                    @else
                                        <div class="w-8 h-8 bg-dark-700 rounded flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm">📺</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="text-sm font-semibold text-accent-400 group-hover:text-accent-300 transition-colors">
                                            {{ $program->channel->name }}
                                        </h4>
                                        <p class="text-xs text-dark-500">
                                            @if($program->channel->country)
                                                <span class="mr-2">{{ $program->channel->country }}</span>
                                            @endif
                                            @if($program->channel->channel_number)
                                                <span>Ch. {{ $program->channel->channel_number }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Program Info -->
                                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-accent-400 transition-colors">
                                    {{ $program->title }}
                                </h3>
                                
                                <!-- Time -->
                                <div class="flex items-center gap-2 text-dark-300 mb-3 flex-wrap">
                                    <svg class="h-4 w-4 text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm">
                                        {{ $program->start_time->format('M j, Y g:i A') }}
                                    </span>
                                    @if($program->start_time > now())
                                        <span class="text-xs text-accent-400">({{ $program->start_time->diffForHumans() }})</span>
                                    @elseif($program->end_time > now())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-600 rounded-full text-xs font-bold text-white uppercase tracking-wider">
                                            <svg class="h-2 w-2" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="10"/>
                                            </svg>
                                            Live Now
                                        </span>
                                    @endif
                                </div>
                                
                                @if($program->description)
                                    <p class="text-dark-400 text-sm line-clamp-2 mb-3">{{ $program->description }}</p>
                                @endif
                                
                                <div class="flex gap-2 flex-wrap">
                                    @if($program->genre)
                                        <span class="px-3 py-1 bg-dark-700/50 text-dark-300 text-xs rounded-full border border-dark-600">
                                            🎭 {{ $program->genre }}
                                        </span>
                                    @endif
                                    @if($program->rating)
                                        <span class="px-3 py-1 bg-dark-700/50 text-dark-300 text-xs rounded-full border border-dark-600">
                                            ⚠️ {{ $program->rating }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($program->image_url)
                                <img src="{{ $program->image_url }}" alt="{{ $program->title }}" class="w-48 h-32 object-cover rounded-lg shadow-lg">
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $programs->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
