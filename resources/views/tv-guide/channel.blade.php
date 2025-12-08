@extends('layouts.app')

@section('title', $channel->name . ' - TV Guide')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('tv-guide.channels', strtolower($channel->country)) }}" class="text-accent-400 hover:text-accent-300">
            ← Back to {{ $channel->country }} Channels
        </a>
    </div>
    
    <!-- Channel Header -->
    <div class="bg-dark-800 rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $channel->name }}</h1>
                @if($channel->channel_number)
                    <p class="text-dark-400">Channel {{ $channel->channel_number }}</p>
                @endif
            </div>
            @if($channel->logo_url)
                <img src="{{ $channel->logo_url }}" alt="{{ $channel->name }}" class="w-20 h-20 object-contain">
            @endif
        </div>
        
        @if($channel->description)
            <p class="text-dark-300 mt-4">{{ $channel->description }}</p>
        @endif
    </div>
    
    <!-- Current Program -->
    @if($currentProgram)
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                    <h2 class="text-2xl font-bold">On Air Now</h2>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-xl border-2 border-red-500/50 bg-gradient-to-br from-red-500/10 via-accent-500/5 to-gh-bg-secondary">
                <div class="absolute top-0 right-0 w-32 h-32 bg-red-500/10 rounded-bl-full"></div>
                <div class="relative p-8">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-600 rounded-full text-xs font-bold text-white uppercase tracking-wider">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="10" cy="10" r="10"/>
                                    </svg>
                                    Live
                                </span>
                                <span class="text-sm text-gh-text-muted">
                                    {{ $currentProgram->start_time->format('g:i A') }} - {{ $currentProgram->end_time->format('g:i A') }}
                                </span>
                            </div>
                            <h3 class="text-3xl font-bold text-white mb-3">{{ $currentProgram->title }}</h3>
                            @if($currentProgram->description)
                                <p class="text-gh-text-muted text-lg mb-4 line-clamp-3">{{ $currentProgram->description }}</p>
                            @endif
                        </div>
                        @if($currentProgram->image_url)
                            <img src="{{ $currentProgram->image_url }}" alt="{{ $currentProgram->title }}" class="ml-6 w-48 h-32 object-cover rounded-lg shadow-xl">
                        @endif
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($currentProgram->genre)
                            <span class="px-3 py-1.5 bg-gh-bg-tertiary rounded-lg text-sm font-medium text-gh-text-muted border border-gh-border">
                                🎭 {{ $currentProgram->genre }}
                            </span>
                        @endif
                        @if($currentProgram->rating)
                            <span class="px-3 py-1.5 bg-gh-bg-tertiary rounded-lg text-sm font-medium text-gh-text-muted border border-gh-border">
                                ⚠️ {{ $currentProgram->rating }}
                            </span>
                        @endif
                        <div class="flex-1 min-w-[200px]">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-gh-text-muted">Progress</span>
                                <span class="text-xs text-gh-text-muted">{{ $progress }}%</span>
                            </div>
                            <div class="w-full h-2 bg-gh-bg-tertiary rounded-full overflow-hidden">
                                <div class="h-full bg-red-500 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mb-8 card p-8 text-center">
            <p class="text-gh-text-muted">No program currently airing. Check upcoming schedule below.</p>
        </div>
    @endif
    
    <!-- Upcoming Programs -->
    <div>
        <h2 class="text-2xl font-bold mb-4">Coming Up Next</h2>
        
        @if($upcomingPrograms->isEmpty())
            <div class="bg-dark-800 rounded-lg p-8 text-center">
                <p class="text-dark-300">No upcoming programs scheduled at this time.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($upcomingPrograms as $program)
                    <div class="bg-dark-800 rounded-lg p-6 hover:bg-dark-700 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-accent-400 mb-2">{{ $program->title }}</h3>
                                <p class="text-dark-300 mb-2">
                                    {{ $program->start_time->format('g:i A') }} - {{ $program->end_time->format('g:i A') }}
                                    <span class="text-dark-500">
                                        ({{ $program->start_time->diffForHumans() }})
                                    </span>
                                </p>
                                @if($program->description)
                                    <p class="text-dark-300 text-sm">{{ $program->description }}</p>
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
        @endif
    </div>
</div>
@endsection
