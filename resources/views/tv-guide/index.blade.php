@extends('layouts.app')

@section('title', 'TV Guide')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-6xl font-extrabold mb-4">
                <span class="bg-gradient-to-r from-accent-400 via-accent-500 to-accent-600 bg-clip-text text-transparent">TV Guide</span>
            </h1>
            <p class="text-xl text-gh-text-muted max-w-2xl mx-auto">
                Explore live TV channels and their current programming from the UK and US. Never miss your favorite shows!
            </p>
        </div>
        
        <!-- Quick Stats -->
        <div class="grid md:grid-cols-3 gap-6 mb-12">
            <div class="card p-6 text-center border-accent-700/30 hover:border-accent-500/50 transition-all">
                <div class="text-4xl mb-3">📺</div>
                <div class="text-3xl font-bold text-accent-400 mb-1">{{ $stats['active_channels'] }}</div>
                <div class="text-sm text-dark-400 uppercase tracking-wide">Active Channels</div>
            </div>
            <div class="card p-6 text-center border-accent-700/30 hover:border-accent-500/50 transition-all">
                <div class="text-4xl mb-3">🎬</div>
                <div class="text-3xl font-bold text-accent-400 mb-1">{{ $stats['currently_airing'] }}</div>
                <div class="text-sm text-dark-400 uppercase tracking-wide">Currently Airing</div>
            </div>
            <div class="card p-6 text-center border-accent-700/30 hover:border-accent-500/50 transition-all">
                <div class="text-4xl mb-3">🗓️</div>
                <div class="text-3xl font-bold text-accent-400 mb-1">{{ $stats['upcoming_week'] }}</div>
                <div class="text-sm text-dark-400 uppercase tracking-wide">Upcoming (7 Days)</div>
            </div>
        </div>
        
        <!-- Country Selection Cards -->
        <div class="grid md:grid-cols-2 gap-8 mb-16">
            <!-- UK TV Guide -->
            <a href="{{ route('tv-guide.channels', 'uk') }}" class="group">
                <div class="card p-12 text-center hover:border-accent-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-accent-500/10 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-accent-500/0 to-accent-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="text-8xl mb-8">🇬🇧</div>
                        <h2 class="text-4xl font-bold mb-4 text-white group-hover:text-accent-400 transition-colors">UK TV Guide</h2>
                        <p class="text-gh-text-muted text-lg mb-6">BBC, ITV, Channel 4, Sky, and more</p>
                        <div class="inline-flex items-center text-accent-400 font-semibold">
                            Browse {{ $stats['uk_channels'] }} channels
                            <svg class="ml-2 h-5 w-5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
            
            <!-- US TV Guide -->
            <a href="{{ route('tv-guide.channels', 'us') }}" class="group">
                <div class="card p-12 text-center hover:border-accent-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-accent-500/10 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-accent-500/0 to-accent-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="text-8xl mb-8">🇺🇸</div>
                        <h2 class="text-4xl font-bold mb-4 text-white group-hover:text-accent-400 transition-colors">US TV Guide</h2>
                        <p class="text-gh-text-muted text-lg mb-6">ABC, CBS, NBC, FOX, CNN, and more</p>
                        <div class="inline-flex items-center text-accent-400 font-semibold">
                            Browse {{ $stats['us_channels'] }} channels
                            <svg class="ml-2 h-5 w-5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Search Section with Enhanced Design -->
        <div class="card p-8 border-accent-700/30">
            <div class="flex items-center mb-6">
                <div class="p-3 bg-accent-500/10 rounded-lg mr-4">
                    <svg class="h-7 w-7 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white">Search TV Programs</h3>
                    <p class="text-sm text-dark-400">Find shows, news, sports, and more across all channels</p>
                </div>
            </div>
            <form action="{{ route('tv-guide.search') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <input 
                    type="text" 
                    name="query" 
                    placeholder="Search for a program, show, or movie..." 
                    class="flex-1 input-field"
                    value="{{ request('query') }}"
                    required
                >
                <select name="country" class="input-field md:w-48">
                    <option value="">All Countries</option>
                    <option value="uk" {{ request('country') === 'uk' ? 'selected' : '' }}>UK</option>
                    <option value="us" {{ request('country') === 'us' ? 'selected' : '' }}>US</option>
                </select>
                <button type="submit" class="btn-primary px-8 whitespace-nowrap">
                    <svg class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
