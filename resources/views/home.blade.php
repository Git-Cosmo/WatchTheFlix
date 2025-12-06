@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<div class="relative min-h-[50vh] flex items-center bg-gradient-to-br from-gh-bg via-gh-bg-secondary to-gh-bg">
    <div class="container mx-auto px-4 py-20 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gh-bg-tertiary/80 border border-gh-border/50 mb-8 backdrop-blur-sm">
                <span class="flex h-2 w-2 relative mr-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-accent-500"></span>
                </span>
                <span class="text-gh-text-muted">Stream with Real-Debrid & TMDB</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6">
                Welcome to
                <span class="block mt-2 bg-gradient-to-r from-accent-400 via-accent-500 to-accent-600 bg-clip-text text-transparent">WatchTheFlix</span>
            </h1>
            <p class="text-xl text-gh-text-muted mb-10 max-w-2xl mx-auto">Stream your favorite movies and TV shows with Real-Debrid integration, TV Guide, and platform availability tracking</p>
            
            @guest
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn-primary px-8 py-4 text-lg glow-accent inline-flex items-center justify-center font-semibold">
                    Get Started
                    <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="{{ route('login') }}" class="btn-secondary px-8 py-4 text-lg font-semibold">Sign In</a>
            </div>
            @endguest
        </div>
    </div>
</div>

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
