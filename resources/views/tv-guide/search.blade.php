@extends('layouts.app')

@section('title', 'Search TV Programs')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('tv-guide.index') }}" class="text-accent-400 hover:text-accent-300">
            ← Back to TV Guide
        </a>
    </div>
    
    <h1 class="text-4xl font-bold mb-8">Search Results</h1>
    
    <!-- Search Form -->
    <div class="bg-dark-800 rounded-lg p-6 mb-8">
        <form action="{{ route('tv-guide.search') }}" method="GET" class="flex gap-4">
            <input 
                type="text" 
                name="query" 
                value="{{ $query }}"
                placeholder="Search for a program..." 
                class="flex-1 bg-dark-700 border border-dark-600 rounded-lg px-4 py-2 text-dark-100 focus:border-accent-500 focus:outline-none"
                required
            >
            <select name="country" class="bg-dark-700 border border-dark-600 rounded-lg px-4 py-2 text-dark-100 focus:border-accent-500 focus:outline-none">
                <option value="">All Countries</option>
                <option value="uk" {{ $country == 'uk' ? 'selected' : '' }}>UK</option>
                <option value="us" {{ $country == 'us' ? 'selected' : '' }}>US</option>
            </select>
            <button type="submit" class="btn-primary">Search</button>
        </form>
    </div>
    
    <!-- Results -->
    @if($programs->isEmpty())
        <div class="bg-dark-800 rounded-lg p-8 text-center">
            <p class="text-dark-300">No programs found for "{{ $query }}"</p>
        </div>
    @else
        <p class="text-dark-300 mb-6">
            Found {{ $programs->total() }} program(s) matching "{{ $query }}"
        </p>
        
        <div class="space-y-4">
            @foreach($programs as $program)
                <div class="bg-dark-800 rounded-lg p-6 hover:bg-dark-700 transition-colors">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-accent-400">{{ $program->title }}</h3>
                                @if($program->isCurrentlyAiring())
                                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">LIVE</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('tv-guide.channel', $program->channel) }}" class="text-accent-500 hover:text-accent-400 text-sm">
                                {{ $program->channel->name }}
                            </a>
                            
                            <p class="text-dark-300 mt-2">
                                {{ $program->start_time->format('M j, Y g:i A') }} - {{ $program->end_time->format('g:i A') }}
                            </p>
                            
                            @if($program->description)
                                <p class="text-dark-300 text-sm mt-2">{{ $program->description }}</p>
                            @endif
                            
                            <div class="flex gap-2 mt-3">
                                @if($program->genre)
                                    <span class="bg-dark-700 text-dark-400 text-xs px-3 py-1 rounded-full">{{ $program->genre }}</span>
                                @endif
                                @if($program->rating)
                                    <span class="bg-dark-700 text-dark-400 text-xs px-3 py-1 rounded-full">{{ $program->rating }}</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($program->image_url)
                            <img src="{{ $program->image_url }}" alt="{{ $program->title }}" class="w-32 h-20 object-cover rounded ml-4">
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $programs->links() }}
        </div>
    @endif
</div>
@endsection
