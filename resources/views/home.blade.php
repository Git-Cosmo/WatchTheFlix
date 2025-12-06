@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section with Gradient -->
<div class="relative min-h-[40vh] flex items-center">
    <!-- Background gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-accent-900/30 via-transparent to-gh-bg pointer-events-none"></div>
    
    <div class="container mx-auto px-4 py-16 relative z-10">
        <div class="max-w-3xl">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-gh-bg-tertiary border border-gh-border mb-6">
                <span class="flex h-2 w-2 relative mr-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-accent-500"></span>
                </span>
                <span class="text-gh-text-muted">Stream with Real-Debrid & TMDB</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight mb-4">
                Welcome to
                <span class="block bg-gradient-to-r from-accent-400 to-accent-600 bg-clip-text text-transparent">WatchTheFlix</span>
            </h1>
            <p class="text-xl text-gh-text-muted mb-8">Stream your favorite movies and TV shows with Real-Debrid integration, TV Guide, and platform availability tracking</p>
            
            @guest
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('register') }}" class="btn-primary glow-accent inline-flex items-center justify-center">
                    Get Started
                    <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="{{ route('login') }}" class="btn-secondary">Sign In</a>
            </div>
            @endguest
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">

    @auth
    <!-- Featured Content -->
    @if($featured->count() > 0)
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-6 text-white">Featured</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featured as $item)
            <a href="{{ route('media.show', $item) }}" class="group card overflow-hidden hover:border-accent-500 transition-all hover:glow-accent">
                <div class="relative overflow-hidden">
                    @if($item->poster_url)
                    <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                    <div class="w-full h-64 bg-gh-bg flex items-center justify-center">
                        <span class="text-gh-text-muted text-4xl">🎬</span>
                    </div>
                    @endif
                    <!-- Gradient overlay on hover -->
                    <div class="absolute inset-0 bg-gradient-to-t from-gh-bg via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2 text-white group-hover:text-accent-400 transition-colors">{{ $item->title }}</h3>
                    <p class="text-sm text-gh-text-muted">{{ $item->release_year }} • {{ ucfirst($item->type) }}</p>
                    @if($item->imdb_rating)
                    <div class="flex items-center mt-2">
                        <svg class="h-4 w-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-sm font-medium text-white">{{ $item->imdb_rating }}/10</span>
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Trending -->
    @if($trending->count() > 0)
    <section>
        <h2 class="text-3xl font-bold mb-6 text-white">Trending Now</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($trending as $item)
            <a href="{{ route('media.show', $item) }}" class="group card overflow-hidden hover:border-accent-500 transition-all">
                <div class="relative overflow-hidden">
                    @if($item->poster_url)
                    <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                    <div class="w-full h-48 bg-gh-bg flex items-center justify-center">
                        <span class="text-gh-text-muted text-2xl">🎬</span>
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-gh-bg via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <div class="p-2">
                    <h3 class="text-sm font-semibold truncate text-white group-hover:text-accent-400 transition-colors">{{ $item->title }}</h3>
                    <p class="text-xs text-gh-text-muted">{{ $item->release_year }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif
    @endauth
</div>
@endsection
