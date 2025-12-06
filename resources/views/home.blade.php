@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="relative min-h-[60vh] flex items-center bg-gradient-to-br from-gh-bg via-gh-bg-secondary to-dark-900">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-accent-600/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 py-24 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gh-bg-tertiary/80 border border-gh-border/50 mb-8 backdrop-blur-sm animate-fade-in-down">
                <span class="flex h-2 w-2 relative mr-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-accent-500"></span>
                </span>
                <span class="text-gh-text-muted">🎬 Stream with Real-Debrid & TMDB</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold tracking-tight mb-6 animate-fade-in">
                Welcome to
                <span class="block mt-3 bg-gradient-to-r from-accent-400 via-accent-500 to-accent-600 bg-clip-text text-transparent animate-gradient">WatchTheFlix</span>
            </h1>
            <p class="text-xl md:text-2xl text-gh-text-muted mb-10 max-w-3xl mx-auto leading-relaxed animate-fade-in-up">
                Your ultimate streaming companion featuring thousands of movies and TV shows, Real-Debrid integration, live TV Guide, and platform availability tracking
            </p>
            
            @guest
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-300">
                <a href="{{ route('register') }}" class="btn-primary px-8 py-4 text-lg glow-accent inline-flex items-center justify-center font-semibold transform hover:scale-105 transition-all">
                    Get Started
                    <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="{{ route('login') }}" class="btn-secondary px-8 py-4 text-lg font-semibold transform hover:scale-105 transition-all">Sign In</a>
            </div>
            @else
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-300">
                <a href="{{ route('media.index') }}" class="btn-primary px-8 py-4 text-lg glow-accent inline-flex items-center justify-center font-semibold transform hover:scale-105 transition-all">
                    Browse Content
                    <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                </a>
                <a href="{{ route('tv-guide.index') }}" class="btn-secondary px-8 py-4 text-lg font-semibold transform hover:scale-105 transition-all inline-flex items-center justify-center">
                    TV Guide
                    <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </a>
            </div>
            @endguest
        </div>
    </div>
</div>

<!-- Features Section -->
@guest
<div class="container mx-auto px-4 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        <div class="card p-8 text-center hover:border-accent-500/50 transition-all group">
            <div class="w-16 h-16 bg-accent-500/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-accent-500/20 transition-all">
                <svg class="h-8 w-8 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Vast Library</h3>
            <p class="text-gh-text-muted">Access thousands of movies and TV shows with rich metadata from TMDB</p>
        </div>
        
        <div class="card p-8 text-center hover:border-accent-500/50 transition-all group">
            <div class="w-16 h-16 bg-accent-500/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-accent-500/20 transition-all">
                <svg class="h-8 w-8 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Live TV Guide</h3>
            <p class="text-gh-text-muted">Browse UK and US TV channels with real-time program schedules</p>
        </div>
        
        <div class="card p-8 text-center hover:border-accent-500/50 transition-all group">
            <div class="w-16 h-16 bg-accent-500/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-accent-500/20 transition-all">
                <svg class="h-8 w-8 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Real-Debrid</h3>
            <p class="text-gh-text-muted">Premium streaming with Real-Debrid integration for enhanced access</p>
        </div>
    </div>
</div>
@endguest

<div class="container mx-auto px-4 py-8">

    @auth
    <!-- Featured Content -->
    @if($featured->count() > 0)
    <section class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-white">Featured</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($featured as $item)
            <a href="{{ route('media.show', $item) }}" class="group">
                <div class="card overflow-hidden hover:border-accent-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-accent-500/10">
                    <div class="relative overflow-hidden aspect-[2/3]">
                        @if($item->poster_url)
                        <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                        <div class="w-full h-full bg-gh-bg flex items-center justify-center">
                            <span class="text-gh-text-muted text-4xl">🎬</span>
                        </div>
                        @endif
                        <!-- Gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <!-- Rating badge -->
                        @if($item->imdb_rating)
                        <div class="absolute top-3 right-3 flex items-center gap-1 px-2 py-1 bg-black/70 backdrop-blur-sm rounded-md">
                            <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="text-sm font-semibold text-white">{{ $item->imdb_rating }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-base font-semibold mb-1 text-white group-hover:text-accent-400 transition-colors line-clamp-1">{{ $item->title }}</h3>
                        <p class="text-sm text-gh-text-muted">{{ $item->release_year }} • {{ ucfirst($item->type) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Trending -->
    @if($trending->count() > 0)
    <section>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-white">Trending Now</h2>
        </div>
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
            @foreach($trending as $item)
            <a href="{{ route('media.show', $item) }}" class="group">
                <div class="card overflow-hidden hover:border-accent-500/50 transition-all duration-300">
                    <div class="relative overflow-hidden aspect-[2/3]">
                        @if($item->poster_url)
                        <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                        <div class="w-full h-full bg-gh-bg flex items-center justify-center">
                            <span class="text-gh-text-muted text-2xl">🎬</span>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-semibold truncate text-white group-hover:text-accent-400 transition-colors">{{ $item->title }}</h3>
                        <p class="text-xs text-gh-text-muted mt-0.5">{{ $item->release_year }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif
    @endauth
</div>
@endsection
