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
            <h2 class="text-2xl font-bold mb-4">Currently Airing</h2>
            <div class="bg-gradient-to-r from-accent-900 to-dark-800 rounded-lg p-6 border-2 border-accent-500">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="text-2xl font-bold text-accent-400">{{ $currentProgram->title }}</h3>
                    <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full">LIVE</span>
                </div>
                <p class="text-dark-300 mb-3">
                    {{ $currentProgram->start_time->format('g:i A') }} - {{ $currentProgram->end_time->format('g:i A') }}
                </p>
                @if($currentProgram->description)
                    <p class="text-dark-200 mb-3">{{ $currentProgram->description }}</p>
                @endif
                <div class="flex gap-2">
                    @if($currentProgram->genre)
                        <span class="bg-dark-700 text-dark-300 text-xs px-3 py-1 rounded-full">{{ $currentProgram->genre }}</span>
                    @endif
                    @if($currentProgram->rating)
                        <span class="bg-dark-700 text-dark-300 text-xs px-3 py-1 rounded-full">{{ $currentProgram->rating }}</span>
                    @endif
                </div>
            </div>
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
