@extends('layouts.app')

@section('title', $country . ' TV Channels')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <a href="{{ route('tv-guide.index') }}" class="inline-flex items-center text-accent-400 hover:text-accent-300 transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to TV Guide
        </a>
    </div>
    
    <h1 class="text-5xl font-extrabold mb-8 bg-gradient-to-r from-accent-400 to-accent-600 bg-clip-text text-transparent">{{ $country }} TV Channels</h1>
    
    @if($channels->isEmpty())
        <div class="card p-12 text-center">
            <div class="text-6xl mb-4">📺</div>
            <p class="text-gh-text-muted text-lg">No TV channels available for {{ $country }} at the moment.</p>
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($channels as $channel)
                <a href="{{ route('tv-guide.channel', $channel) }}" class="group">
                    <div class="card p-6 hover:border-accent-500 transition-all hover:glow-accent">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white group-hover:text-accent-400 transition-colors mb-1">
                                    {{ $channel->name }}
                                </h3>
                                @if($channel->channel_number)
                                    <p class="text-sm text-gh-text-muted">Channel {{ $channel->channel_number }}</p>
                                @endif
                            </div>
                            @if($channel->logo_url)
                                <img src="{{ $channel->logo_url }}" alt="{{ $channel->name }}" class="w-14 h-14 object-contain rounded-lg">
                            @else
                                <div class="w-14 h-14 bg-gh-bg-tertiary rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">📺</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($channel->description)
                            <p class="text-gh-text-muted text-sm line-clamp-2 mb-3">
                                {{ $channel->description }}
                            </p>
                        @endif
                        
                        <div class="inline-flex items-center text-accent-400 text-sm font-medium mt-2">
                            View schedule
                            <svg class="ml-1 h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
