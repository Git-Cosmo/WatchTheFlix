@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="relative">
    <!-- Hero Section with Featured Content -->
    @if($featured->isNotEmpty())
    <div class="relative h-[70vh] min-h-[500px] overflow-hidden bg-gradient-to-b from-gh-bg to-gh-bg-secondary">
        @php
            $hero = $featured->first();
        @endphp
        
        <!-- Background Image -->
        @if($hero->backdrop_url)
        <div class="absolute inset-0">
            <img src="{{ $hero->backdrop_url }}" alt="{{ $hero->title }}" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-gh-bg via-gh-bg/80 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-gh-bg via-transparent to-gh-bg/50"></div>
        </div>
        @endif
        
        <!-- Content -->
        <div class="relative container mx-auto px-4 h-full flex items-center">
            <div class="max-w-2xl space-y-6">
                @if($hero->poster_url)
                <div class="flex items-center gap-4">
                    <img src="{{ $hero->poster_url }}" alt="{{ $hero->title }}" class="w-24 h-36 object-cover rounded-lg shadow-2xl border-2 border-accent-500/30">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 bg-accent-600 rounded-full text-xs font-bold uppercase">Featured</span>
                            @if($hero->imdb_rating)
                            <div class="flex items-center gap-1 px-2 py-1 bg-black/60 rounded">
                                <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-sm font-bold">{{ number_format($hero->imdb_rating, 1) }}</span>
                            </div>
                            @endif
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-2 text-white drop-shadow-2xl">{{ $hero->title }}</h1>
                    </div>
                </div>
                @else
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 text-white drop-shadow-2xl">{{ $hero->title }}</h1>
                @endif
                
                @if($hero->overview)
                <p class="text-lg text-gray-200 leading-relaxed line-clamp-3 drop-shadow-lg">{{ $hero->overview }}</p>
                @endif
                
                <div class="flex items-center gap-3 flex-wrap">
                    @if($hero->release_year)
                    <span class="text-sm text-gray-300">{{ $hero->release_year }}</span>
                    <span class="text-gray-600">•</span>
                    @endif
                    @if($hero->runtime)
                    <span class="text-sm text-gray-300">{{ $hero->runtime }} min</span>
                    <span class="text-gray-600">•</span>
                    @endif
                    @if($hero->genres && is_array($hero->genres) && count($hero->genres) > 0)
                    <div class="flex gap-2">
                        @foreach(array_slice($hero->genres, 0, 3) as $genre)
                        <span class="text-xs px-2 py-1 bg-white/10 backdrop-blur rounded-full text-gray-300">{{ $genre }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                
                <div class="flex gap-3">
                    <a href="{{ $hero->getRouteUrl() }}" class="btn-primary text-lg px-8 py-3 inline-flex items-center gap-2">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                        </svg>
                        Watch Now
                    </a>
                    <a href="{{ $hero->getRouteUrl() }}" class="btn-secondary text-lg px-8 py-3 inline-flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        More Info
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="container mx-auto px-4 py-8">
        
        <!-- Quick Filter Tabs -->
        <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2">
            <button class="px-4 py-2 rounded-lg bg-accent-500 text-white text-sm font-semibold whitespace-nowrap hover:bg-accent-600 transition-colors">
                🏠 All
            </button>
            <button class="px-4 py-2 rounded-lg bg-gh-bg-light text-gh-text-muted text-sm font-semibold whitespace-nowrap hover:bg-gh-bg-lighter hover:text-white transition-colors">
                🎬 Movies
            </button>
            <button class="px-4 py-2 rounded-lg bg-gh-bg-light text-gh-text-muted text-sm font-semibold whitespace-nowrap hover:bg-gh-bg-lighter hover:text-white transition-colors">
                📺 TV Shows
            </button>
            <button class="px-4 py-2 rounded-lg bg-gh-bg-light text-gh-text-muted text-sm font-semibold whitespace-nowrap hover:bg-gh-bg-lighter hover:text-white transition-colors">
                🔥 Trending
            </button>
            <button class="px-4 py-2 rounded-lg bg-gh-bg-light text-gh-text-muted text-sm font-semibold whitespace-nowrap hover:bg-gh-bg-lighter hover:text-white transition-colors">
                ⭐ Featured
            </button>
        </div>
        
        <!-- Trending Section - Modern Carousel -->
        @if($trending->count() > 0)
        <section class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                    <span class="text-2xl md:text-3xl animate-pulse">🔥</span> 
                    <span class="bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text text-transparent">Trending Now</span>
                </h2>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gh-text-muted px-3 py-1 bg-gh-bg-light rounded-full">{{ $trending->count() }} items</span>
                    <button aria-label="Scroll left" onclick="scrollCarousel('trending-carousel', -300)" class="p-2 bg-gh-bg-light hover:bg-accent-500 rounded-full transition-colors duration-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button aria-label="Scroll right" onclick="scrollCarousel('trending-carousel', 300)" class="p-2 bg-gh-bg-light hover:bg-accent-500 rounded-full transition-colors duration-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
            <div id="trending-carousel" class="flex gap-4 overflow-x-auto pb-4 scroll-smooth snap-x snap-mandatory scrollbar-thin scrollbar-thumb-accent-500 scrollbar-track-gh-bg-light rounded-lg" style="scrollbar-width: thin;">
                @foreach($trending as $index => $item)
                <a href="{{ $item->getRouteUrl() }}" aria-label="View {{ $item->title }}" class="group flex-shrink-0 block w-36 sm:w-44 md:w-52 snap-start">
                    <div class="relative overflow-hidden rounded-xl aspect-[2/3] border-2 border-transparent hover:border-accent-500 transition-all duration-300 hover:scale-110 hover:shadow-2xl hover:shadow-accent-500/40 hover:z-10 bg-gh-bg-secondary">
                        @if($item->poster_url)
                        <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" loading="lazy">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-accent-900 to-gh-bg flex items-center justify-center">
                            <span class="text-4xl">🔥</span>
                        </div>
                        @endif
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-300"></div>
                        
                        <!-- Trending Rank Badge -->
                        <div class="absolute top-2 left-2 w-10 h-10 flex items-center justify-center bg-gradient-to-br from-orange-500 via-red-500 to-pink-600 rounded-lg shadow-lg transform -rotate-6 group-hover:rotate-0 transition-transform duration-300">
                            <span class="text-white font-black text-lg">{{ $index + 1 }}</span>
                        </div>
                        
                        <!-- Rating Badge -->
                        @if($item->imdb_rating || $item->vote_average)
                        <div class="absolute top-2 right-2 flex items-center gap-1 px-2 py-1 bg-black/90 backdrop-blur-md rounded-lg shadow-lg border border-yellow-500/30">
                            <svg class="h-3.5 w-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="font-bold text-white text-sm">{{ number_format($item->imdb_rating ?? $item->vote_average, 1) }}</span>
                        </div>
                        @endif
                        
                        <!-- Content Info -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 transform translate-y-0 group-hover:translate-y-0 transition-transform duration-300">
                            <h3 class="text-sm font-bold text-white line-clamp-2 mb-1 drop-shadow-lg">{{ $item->title }}</h3>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-300 font-medium">{{ $item->release_year }}</span>
                                @if($item->genres && is_array($item->genres) && count($item->genres) > 0)
                                <span class="text-gray-400">{{ $item->genres[0] }}</span>
                                @endif
                            </div>
                            <!-- Play Button on Hover -->
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 mt-2">
                                <div class="flex items-center justify-center gap-2 bg-accent-500 hover:bg-accent-600 rounded-lg py-2 px-3 text-sm font-bold">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                                    </svg>
                                    Watch
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shine Effect on Hover -->
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform -skew-x-12 group-hover:translate-x-full transition-transform duration-1000"></div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif
        
        <!-- Featured & Latest in Compact Grid -->
        @if($featured->count() > 0 || $latestMovies->count() > 0)
        <section class="mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Featured Content -->
                @if($featured->count() > 0)
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <span class="text-xl">⭐</span> Featured
                        </h2>
                        <a href="{{ route('media.index', ['sort' => 'rating']) }}" class="text-xs text-accent-400 hover:text-accent-300 flex items-center gap-1">
                            View All <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2">
                        @foreach($featured->take(8) as $item)
                        <a href="{{ $item->getRouteUrl() }}" class="group block relative">
                            <div class="relative overflow-hidden rounded-lg aspect-[2/3] border border-gh-border hover:border-yellow-500 transition-all duration-200 hover:scale-105 hover:shadow-lg hover:shadow-yellow-500/50 hover:z-10">
                                @if($item->poster_url)
                                <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" loading="lazy">
                                @else
                                <div class="w-full h-full bg-gh-bg flex items-center justify-center">
                                    <span class="text-xl">⭐</span>
                                </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                                @if($item->imdb_rating)
                                <div class="absolute top-1 right-1 px-1 py-0.5 bg-black/90 rounded text-xs font-bold text-yellow-400">
                                    {{ $item->imdb_rating }}
                                </div>
                                @endif
                                <div class="absolute bottom-0 left-0 right-0 p-1.5 transform translate-y-full group-hover:translate-y-0 transition-transform duration-200">
                                    <h3 class="text-xs font-bold text-white line-clamp-2">{{ $item->title }}</h3>
                                </div>
                                <!-- Play Button on Hover -->
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <div class="w-10 h-10 rounded-full bg-accent-500/90 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Latest Movies -->
                @if($latestMovies->count() > 0)
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <span class="text-xl">🎬</span> Latest Movies
                        </h2>
                        <a href="{{ route('media.index', ['type' => 'movie', 'sort' => 'latest']) }}" class="text-xs text-accent-400 hover:text-accent-300 flex items-center gap-1">
                            View All <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2">
                        @foreach($latestMovies->take(8) as $item)
                        <a href="{{ $item->getRouteUrl() }}" class="group block relative">
                            <div class="relative overflow-hidden rounded-lg aspect-[2/3] border border-gh-border hover:border-accent-500 transition-all duration-200 hover:scale-105 hover:shadow-lg hover:shadow-accent-500/50 hover:z-10">
                                @if($item->poster_url)
                                <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" loading="lazy">
                                @else
                                <div class="w-full h-full bg-gh-bg flex items-center justify-center">
                                    <span class="text-xl">🎬</span>
                                </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                                @if($item->imdb_rating)
                                <div class="absolute top-1 right-1 px-1 py-0.5 bg-black/90 rounded text-xs font-bold text-yellow-400">
                                    {{ $item->imdb_rating }}
                                </div>
                                @endif
                                <div class="absolute top-1 left-1 px-1.5 py-0.5 bg-accent-500 rounded text-xs font-bold">NEW</div>
                                <div class="absolute bottom-0 left-0 right-0 p-1.5 transform translate-y-full group-hover:translate-y-0 transition-transform duration-200">
                                    <h3 class="text-xs font-bold text-white line-clamp-2">{{ $item->title }}</h3>
                                </div>
                                <!-- Play Button on Hover -->
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <div class="w-10 h-10 rounded-full bg-accent-500/90 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </section>
        @endif

        <!-- Latest TV Shows - Compact Grid -->
        @if($latestTvShows->count() > 0)
        <section class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-xl">📺</span> Latest TV Shows
                </h2>
                <a href="{{ route('media.index', ['type' => 'series', 'sort' => 'latest']) }}" class="text-xs text-accent-400 hover:text-accent-300 flex items-center gap-1">
                    View All <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-2">
                @foreach($latestTvShows->take(10) as $item)
                <a href="{{ $item->getRouteUrl() }}" class="group block relative">
                    <div class="relative overflow-hidden rounded-lg aspect-[2/3] border border-gh-border hover:border-blue-500 transition-all duration-200 hover:scale-105 hover:shadow-lg hover:shadow-blue-500/50 hover:z-10">
                        @if($item->poster_url)
                        <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" loading="lazy">
                        @else
                        <div class="w-full h-full bg-gh-bg flex items-center justify-center">
                            <span class="text-xl">📺</span>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                        @if($item->imdb_rating)
                        <div class="absolute top-1 right-1 px-1 py-0.5 bg-black/90 rounded text-xs font-bold text-yellow-400">
                            {{ $item->imdb_rating }}
                        </div>
                        @endif
                        <div class="absolute top-1 left-1 px-1.5 py-0.5 bg-blue-500 rounded text-xs font-bold">NEW</div>
                        <div class="absolute bottom-0 left-0 right-0 p-1.5 transform translate-y-full group-hover:translate-y-0 transition-transform duration-200">
                            <h3 class="text-xs font-bold text-white line-clamp-2">{{ $item->title }}</h3>
                        </div>
                        <!-- Play Button on Hover -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="w-10 h-10 rounded-full bg-blue-500/90 flex items-center justify-center">
                                <svg class="h-5 w-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- More Movies - Compact Grid -->
        @if($latestMovies->count() > 8)
        <section class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xl font-bold text-white">More Movies to Explore</h2>
                <a href="{{ route('media.index', ['type' => 'movie']) }}" class="text-xs text-accent-400 hover:text-accent-300 flex items-center gap-1">
                    Browse All <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-2">
                @foreach($latestMovies->slice(8)->take(10) as $item)
                <a href="{{ $item->getRouteUrl() }}" class="group block relative">
                    <div class="relative overflow-hidden rounded-lg aspect-[2/3] border border-gh-border hover:border-accent-500 transition-all duration-200 hover:scale-105 hover:shadow-lg hover:shadow-accent-500/50 hover:z-10">
                        @if($item->poster_url)
                        <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" loading="lazy">
                        @else
                        <div class="w-full h-full bg-gh-bg flex items-center justify-center">
                            <span class="text-xl">🎬</span>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                        @if($item->imdb_rating)
                        <div class="absolute top-1 right-1 px-1 py-0.5 bg-black/90 rounded text-xs font-bold text-yellow-400">
                            {{ $item->imdb_rating }}
                        </div>
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 p-1.5 transform translate-y-full group-hover:translate-y-0 transition-transform duration-200">
                            <h3 class="text-xs font-bold text-white line-clamp-2">{{ $item->title }}</h3>
                        </div>
                        <!-- Play Button on Hover -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="w-10 h-10 rounded-full bg-accent-500/90 flex items-center justify-center">
                                <svg class="h-5 w-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Bottom CTA for Guests - Sleek Banner -->
        @guest
        <section class="mb-6">
            <div class="relative overflow-hidden rounded-xl border border-accent-500/30 bg-gradient-to-br from-accent-500/10 via-blue-500/10 to-purple-500/10 p-6 md:p-8">
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAxOGMzLjMxNCAwIDYgMi42ODYgNiA2cy0yLjY4NiA2LTYgNi02LTIuNjg2LTYtNiAyLjY4Ni02IDYtNnoiIHN0cm9rZT0iIzAwMCIgc3Ryb2tlLXdpZHRoPSIyIi8+PC9nPjwvc3ZnPg==')]"></div>
                </div>
                <div class="relative text-center">
                    <h2 class="text-2xl md:text-3xl font-bold mb-2 text-white">Love What You See?</h2>
                    <p class="text-sm text-gh-text-muted mb-4 max-w-xl mx-auto">
                        Join thousands streaming unlimited movies, TV shows & live channels. Free to start!
                    </p>
                    <div class="flex gap-2 justify-center flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-primary px-6 py-2.5 inline-flex items-center">
                            🚀 Get Started Free
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-secondary px-6 py-2.5">Sign In</a>
                    </div>
                </div>
            </div>
        </section>
        @endguest

        <!-- Feature Highlights - Compact Cards -->
        <section class="mb-6">
            <div class="grid grid-cols-3 gap-2 md:gap-3">
                <a href="{{ route('media.index') }}" class="card p-3 md:p-4 hover:border-accent-500/50 transition-all group text-center">
                    <div class="text-2xl md:text-3xl mb-1 md:mb-2 group-hover:scale-110 transition-transform">🎬</div>
                    <h3 class="font-bold text-white text-xs md:text-sm mb-0.5 group-hover:text-accent-400">Browse Library</h3>
                    <p class="text-xs text-gh-text-muted hidden md:block">Thousands of titles</p>
                </a>
                <a href="{{ route('tv-guide.index') }}" class="card p-3 md:p-4 hover:border-accent-500/50 transition-all group text-center">
                    <div class="text-2xl md:text-3xl mb-1 md:mb-2 group-hover:scale-110 transition-transform">📺</div>
                    <h3 class="font-bold text-white text-xs md:text-sm mb-0.5 group-hover:text-accent-400">TV Guide</h3>
                    <p class="text-xs text-gh-text-muted hidden md:block">Live schedules</p>
                </a>
                <a href="{{ route('forum.index') }}" class="card p-3 md:p-4 hover:border-accent-500/50 transition-all group text-center">
                    <div class="text-2xl md:text-3xl mb-1 md:mb-2 group-hover:scale-110 transition-transform">💬</div>
                    <h3 class="font-bold text-white text-xs md:text-sm mb-0.5 group-hover:text-accent-400">Community</h3>
                    <p class="text-xs text-gh-text-muted hidden md:block">Join discussions</p>
                </a>
            </div>
        </section>

        <!-- Pro Tip for Users -->
        <div class="text-center text-xs text-gh-text-muted mb-4">
            💡 <span class="font-semibold">Pro tip:</span> Hover over any poster to see quick actions and info
        </div>

    </div>
</div>
@endsection
