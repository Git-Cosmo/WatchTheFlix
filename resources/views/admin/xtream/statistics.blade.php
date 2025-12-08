@extends('layouts.app')

@section('title', 'Xtream API Statistics')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">API Statistics</h1>
        <a href="{{ route('admin.xtream.index') }}" class="btn-secondary">Back to Dashboard</a>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card p-6">
            <div class="text-3xl font-bold text-accent-500 mb-2">{{ $stats['total_api_requests'] }}</div>
            <div class="text-dark-400 text-sm">Total API Requests</div>
        </div>

        <div class="card p-6">
            <div class="text-3xl font-bold text-green-500 mb-2">{{ $stats['active_tokens'] }}</div>
            <div class="text-dark-400 text-sm">Active Tokens</div>
        </div>

        <div class="card p-6">
            <div class="text-3xl font-bold text-blue-500 mb-2">{{ $stats['channels_by_country']->sum('total') }}</div>
            <div class="text-dark-400 text-sm">Total Channels</div>
        </div>

        <div class="card p-6">
            <div class="text-3xl font-bold text-purple-500 mb-2">{{ $stats['vod_by_type']->sum('total') }}</div>
            <div class="text-dark-400 text-sm">Total VOD</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Channels by Country -->
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-4">Channels by Country</h2>
            <div class="space-y-3">
                @foreach($stats['channels_by_country'] as $stat)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-accent-500/10 rounded-lg flex items-center justify-center">
                            <span class="font-bold text-accent-400">{{ strtoupper(substr($stat->country, 0, 2)) }}</span>
                        </div>
                        <span class="font-medium">{{ $stat->country }}</span>
                    </div>
                    <span class="text-2xl font-bold text-accent-500">{{ $stat->total }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- VOD by Type -->
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-4">VOD by Type</h2>
            <div class="space-y-3">
                @foreach($stats['vod_by_type'] as $stat)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 {{ $stat->type === 'movie' ? 'bg-blue-500/10' : 'bg-purple-500/10' }} rounded-lg flex items-center justify-center">
                            @if($stat->type === 'movie')
                            <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                            </svg>
                            @else
                            <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            @endif
                        </div>
                        <span class="font-medium capitalize">{{ $stat->type }}</span>
                    </div>
                    <span class="text-2xl font-bold {{ $stat->type === 'movie' ? 'text-blue-500' : 'text-purple-500' }}">{{ $stat->total }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
