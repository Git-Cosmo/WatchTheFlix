@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-breadcrumbs :items="[
        ['label' => 'Analytics', 'url' => route('admin.analytics.index')]
    ]" />

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ __('Analytics Dashboard') }}</h1>
        
        <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex gap-2">
            <select name="period" onchange="this.form.submit()" 
                    class="px-4 py-2 bg-dark-700 border border-dark-600 rounded-lg focus:outline-none focus:border-accent-500">
                <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 Days</option>
                <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 Days</option>
            </select>
        </form>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Streams -->
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-dark-400 text-sm">{{ __('Total Streams') }}</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totalStreams) }}</p>
                </div>
                <div class="bg-accent-500/10 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Unique Users -->
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-dark-400 text-sm">{{ __('Unique Users') }}</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($uniqueUsers) }}</p>
                    <p class="text-xs text-dark-500 mt-1">of {{ number_format($totalUsers) }} total</p>
                </div>
                <div class="bg-blue-500/10 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Current Connections -->
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-dark-400 text-sm">{{ __('Live Now') }}</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($currentConnections) }}</p>
                    <p class="text-xs text-green-500 mt-1">{{ __('Concurrent') }}</p>
                </div>
                <div class="bg-green-500/10 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Bandwidth Usage -->
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-dark-400 text-sm">{{ __('Bandwidth') }}</p>
                    <p class="text-3xl font-bold mt-2">{{ $formattedBandwidth }}</p>
                    <p class="text-xs text-dark-500 mt-1">{{ __('Total transferred') }}</p>
                </div>
                <div class="bg-purple-500/10 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <p class="text-dark-400 text-sm">{{ __('Active Subscriptions') }}</p>
            <p class="text-2xl font-bold mt-2">{{ number_format($activeSubscriptions) }}</p>
        </div>
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <p class="text-dark-400 text-sm">{{ __('Expiring Soon') }}</p>
            <p class="text-2xl font-bold mt-2 text-orange-500">{{ number_format($expiringSoon) }}</p>
        </div>
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <p class="text-dark-400 text-sm">{{ __('Avg. Viewing Time') }}</p>
            <p class="text-2xl font-bold mt-2">{{ $avgViewingTime }} min</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Popular Live Channels -->
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <h2 class="text-xl font-semibold mb-4">{{ __('Popular Live Channels') }}</h2>
            @if($popularLive->count() > 0)
                <div class="space-y-3">
                    @foreach($popularLive as $item)
                        <div class="flex items-center justify-between">
                            <span class="text-dark-300">Channel ID: {{ $item->stream_id }}</span>
                            <span class="text-accent-500 font-semibold">{{ number_format($item->views) }} views</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-dark-400">{{ __('No data available') }}</p>
            @endif
        </div>

        <!-- Popular VOD Content -->
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <h2 class="text-xl font-semibold mb-4">{{ __('Popular VOD Content') }}</h2>
            @if($popularVod->count() > 0)
                <div class="space-y-3">
                    @foreach($popularVod as $item)
                        <div class="flex items-center justify-between">
                            <span class="text-dark-300">Media ID: {{ $item->stream_id }}</span>
                            <span class="text-accent-500 font-semibold">{{ number_format($item->views) }} views</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-dark-400">{{ __('No data available') }}</p>
            @endif
        </div>
    </div>

    <!-- Quality & Device Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quality Distribution -->
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <h2 class="text-xl font-semibold mb-4">{{ __('Quality Distribution') }}</h2>
            @if($qualityDistribution->count() > 0)
                <div class="space-y-3">
                    @foreach($qualityDistribution as $quality => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-dark-300">{{ $quality }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-32 h-2 bg-dark-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-accent-500 rounded-full" 
                                         style="width: {{ ($count / $totalStreams) * 100 }}%"></div>
                                </div>
                                <span class="text-accent-500 font-semibold">{{ number_format($count) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-dark-400">{{ __('No data available') }}</p>
            @endif
        </div>

        <!-- Device Distribution -->
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-700">
            <h2 class="text-xl font-semibold mb-4">{{ __('Device Types') }}</h2>
            @if($deviceDistribution->count() > 0)
                <div class="space-y-3">
                    @foreach($deviceDistribution as $device => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-dark-300">{{ $device }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-32 h-2 bg-dark-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" 
                                         style="width: {{ ($count / $totalStreams) * 100 }}%"></div>
                                </div>
                                <span class="text-blue-500 font-semibold">{{ number_format($count) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-dark-400">{{ __('No data available') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
