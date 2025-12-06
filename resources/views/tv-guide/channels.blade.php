@extends('layouts.app')

@section('title', $country . ' TV Channels')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('tv-guide.index') }}" class="text-accent-400 hover:text-accent-300">
            ← Back to TV Guide
        </a>
    </div>
    
    <h1 class="text-4xl font-bold mb-8">{{ $country }} TV Channels</h1>
    
    @if($channels->isEmpty())
        <div class="bg-dark-800 rounded-lg p-8 text-center">
            <p class="text-dark-300">No TV channels available for {{ $country }} at the moment.</p>
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($channels as $channel)
                <a href="{{ route('tv-guide.channel', $channel) }}" class="group">
                    <div class="bg-dark-800 rounded-lg p-6 hover:bg-dark-700 transition-colors border-2 border-transparent hover:border-accent-500">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-accent-400 group-hover:text-accent-300">
                                    {{ $channel->name }}
                                </h3>
                                @if($channel->channel_number)
                                    <p class="text-sm text-dark-400">Channel {{ $channel->channel_number }}</p>
                                @endif
                            </div>
                            @if($channel->logo_url)
                                <img src="{{ $channel->logo_url }}" alt="{{ $channel->name }}" class="w-12 h-12 object-contain">
                            @endif
                        </div>
                        
                        @if($channel->description)
                            <p class="text-dark-300 text-sm line-clamp-2">
                                {{ $channel->description }}
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
