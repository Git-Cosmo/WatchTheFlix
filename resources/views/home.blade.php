@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="mb-12">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Welcome to WatchTheFlix</h1>
        <p class="text-xl text-dark-300 mb-8">Stream your favorite movies and TV shows with Real-Debrid integration</p>
        
        @guest
        <div class="flex space-x-4">
            <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
            <a href="{{ route('login') }}" class="btn-secondary">Sign In</a>
        </div>
        @endguest
    </div>

    @auth
    <!-- Featured Content -->
    @if($featured->count() > 0)
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Featured</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featured as $item)
            <a href="{{ route('media.show', $item) }}" class="card overflow-hidden hover:border-accent-500 transition">
                @if($item->poster_url)
                <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-64 object-cover">
                @else
                <div class="w-full h-64 bg-dark-800 flex items-center justify-center">
                    <span class="text-dark-500 text-4xl">🎬</span>
                </div>
                @endif
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">{{ $item->title }}</h3>
                    <p class="text-sm text-dark-400">{{ $item->release_year }} • {{ ucfirst($item->type) }}</p>
                    @if($item->imdb_rating)
                    <p class="text-sm text-accent-500 mt-2">⭐ {{ $item->imdb_rating }}/10</p>
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
        <h2 class="text-2xl font-bold mb-6">Trending Now</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($trending as $item)
            <a href="{{ route('media.show', $item) }}" class="card overflow-hidden hover:border-accent-500 transition">
                @if($item->poster_url)
                <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-dark-800 flex items-center justify-center">
                    <span class="text-dark-500 text-2xl">🎬</span>
                </div>
                @endif
                <div class="p-2">
                    <h3 class="text-sm font-semibold truncate">{{ $item->title }}</h3>
                    <p class="text-xs text-dark-400">{{ $item->release_year }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif
    @endauth
</div>
@endsection
