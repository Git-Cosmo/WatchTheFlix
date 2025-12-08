@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="relative">
    <!-- Compact Header with Stats and Quick Actions -->
    <div class="bg-gradient-to-r from-accent-500/5 via-blue-500/5 to-purple-500/5 border-b border-gh-border">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">WatchTheFlix</h1>
                    <p class="text-sm text-gh-text-muted">Your Ultimate Streaming Platform</p>
                </div>
                
                <!-- Quick Stats (inline) -->
                @auth
                @if($stats)
                <div class="hidden md:flex items-center gap-6 text-sm">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-accent-400">{{ $stats['total_media'] }}</div>
                        <div class="text-xs text-gh-text-muted">Total</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-accent-400">{{ $stats['movies'] }}</div>
                        <div class="text-xs text-gh-text-muted">Movies</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-accent-400">{{ $stats['series'] }}</div>
                        <div class="text-xs text-gh-text-muted">Series</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-400">{{ $stats['watchlist_count'] }}</div>
                        <div class="text-xs text-gh-text-muted">Watchlist</div>
                    </div>
                </div>
                @endif
                @endauth
                
                <!-- Quick Actions for Guests -->
                @guest
                <div class="flex items-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Get Started</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Sign In</a>
                </div>
                @endguest
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-4">
        
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
        
        <!-- Trending Section - Horizontal Scroll -->
        @if($trending->count() > 0)
        <section class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="text-xl">🔥</span> Trending Now
                </h2>
                <span class="text-xs text-gh-text-muted">{{ $trending->count() }} items</span>
            </div>
            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-accent-500 scrollbar-track-gh-bg">
                @foreach($trending as $item)
                <a href="{{ $item->getRouteUrl() }}" class="group flex-shrink-0 block w-28 sm:w-32">
                    <div class="relative overflow-hidden rounded-lg aspect-[2/3] border border-gh-border hover:border-accent-500 transition-all duration-200 hover:scale-105 hover:shadow-lg hover:shadow-accent-500/50">
                        @if($item->poster_url)
                        <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" loading="lazy">
                        @else
                        <div class="w-full h-full bg-gh-bg flex items-center justify-center">
                            <span class="text-xl">🔥</span>
                        </div>
                        @endif
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                        <!-- Rating Badge -->
                        @if($item->imdb_rating)
                        <div class="absolute top-1 right-1 flex items-center gap-0.5 px-1 py-0.5 bg-black/90 backdrop-blur-sm rounded text-xs">
                            <svg class="h-2.5 w-2.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="font-bold text-white text-xs">{{ $item->imdb_rating }}</span>
                        </div>
                        @endif
                        <!-- Quick Info on Hover -->
                        <div class="absolute bottom-0 left-0 right-0 p-2 transform translate-y-full group-hover:translate-y-0 transition-transform duration-200">
                            <h3 class="text-xs font-bold text-white line-clamp-2 mb-0.5">{{ $item->title }}</h3>
                            <p class="text-xs text-gray-300">{{ $item->release_year }}</p>
                        </div>
                        <!-- Trending Badge -->
                        <div class="absolute top-1 left-1 px-1.5 py-0.5 bg-gradient-to-r from-orange-500 to-red-500 rounded text-xs font-bold flex items-center gap-1">
                            🔥
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
