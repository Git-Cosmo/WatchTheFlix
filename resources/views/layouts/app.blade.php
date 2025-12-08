<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @hasSection('seo')
        @yield('seo')
    @else
        <title>{{ config('app.name', 'WatchTheFlix') }} - @yield('title', 'Home')</title>
        <meta name="description" content="Stream your favorite movies and TV shows">
        <link rel="canonical" href="{{ url()->current() }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-dark-950 text-dark-100 flex flex-col">
    <!-- Navigation -->
    <nav class="bg-gh-bg-secondary border-b border-gh-border sticky top-0 z-50 backdrop-blur-sm bg-gh-bg-secondary/95">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-2xl font-bold bg-gradient-to-r from-accent-400 to-accent-600 bg-clip-text text-transparent hover:from-accent-300 hover:to-accent-500 transition-all">
                        WatchTheFlix
                    </a>
                    
                    @auth
                    <div class="hidden md:flex space-x-2">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">
                            <svg class="h-4 w-4 mr-1.5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Home
                        </a>
                        <a href="{{ route('media.index') }}" class="nav-link {{ request()->routeIs('media.*') ? 'nav-link-active' : '' }}">
                            <svg class="h-4 w-4 mr-1.5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                            </svg>
                            Browse
                        </a>
                        <a href="{{ route('tv-guide.index') }}" class="nav-link {{ request()->routeIs('tv-guide.*') ? 'nav-link-active' : '' }}">
                            <svg class="h-4 w-4 mr-1.5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            TV Guide
                        </a>
                        <a href="{{ route('watchlist.index') }}" class="nav-link {{ request()->routeIs('watchlist.*') ? 'nav-link-active' : '' }}">
                            <svg class="h-4 w-4 mr-1.5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            Watchlist
                        </a>
                        <a href="{{ route('forum.index') }}" class="nav-link {{ request()->routeIs('forum.*') ? 'nav-link-active' : '' }}">
                            <svg class="h-4 w-4 mr-1.5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                            Forum
                        </a>
                        @can('viewAny', App\Models\User::class)
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'nav-link-active' : '' }}">
                            <svg class="h-4 w-4 mr-1.5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Admin
                        </a>
                        @endcan
                    </div>
                    @endauth
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                    <!-- Quick Search -->
                    <form method="GET" action="{{ route('media.index') }}" class="hidden lg:block">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Quick search..." class="input-field w-64 pl-10 py-2 text-sm" value="{{ request('search') }}">
                            <svg class="h-5 w-5 text-dark-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </form>
                    @endauth

                    @guest
                    <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary">Sign Up</a>
                    @else
                    <!-- Notifications -->
                    <div class="relative">
                        <button onclick="toggleDropdown('notifications-menu')" class="relative text-dark-300 hover:text-dark-100 p-2">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-accent-500"></span>
                            </span>
                            @endif
                        </button>
                        
                        <div id="notifications-menu" class="hidden absolute right-0 mt-2 w-80 bg-dark-800 border border-dark-700 rounded-lg shadow-lg z-10 max-h-96 overflow-y-auto">
                            <div class="p-4 border-b border-dark-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-dark-100">Notifications</h3>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-accent-400 hover:text-accent-300">
                                            Mark all read
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            
                            @forelse(auth()->user()->notifications->take(10) as $notification)
                            <div class="p-4 border-b border-dark-700 {{ $notification->read_at ? 'bg-dark-800' : 'bg-dark-750' }} hover:bg-dark-700 transition-colors">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @php
                                            $notificationType = $notification->data['type'] ?? 'default';
                                        @endphp
                                        @if($notificationType === 'welcome')
                                        <div class="w-8 h-8 bg-accent-500/20 rounded-full flex items-center justify-center">
                                            <svg class="h-4 w-4 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                                            </svg>
                                        </div>
                                        @else
                                        <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                                            <svg class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-dark-100">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                        <p class="text-xs text-dark-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notification->read_at)
                                    <div class="flex-shrink-0">
                                        <span class="inline-block w-2 h-2 bg-accent-500 rounded-full"></span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-dark-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-2 text-sm text-dark-400">No notifications yet</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="relative">
                        <button onclick="toggleDropdown('user-menu')" class="flex items-center space-x-2 text-dark-300 hover:text-dark-100">
                            @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border-2 border-accent-500">
                            @else
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center text-sm font-medium text-white">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            @endif
                            <span>{{ auth()->user()->name }}</span>
                        </button>
                        
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-dark-800 border border-dark-700 rounded-lg shadow-lg z-10">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-dark-100">Profile</a>
                            <a href="{{ route('profile.settings') }}" class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-dark-100">Settings</a>
                            @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-dark-100 border-t border-dark-700">
                                <svg class="h-4 w-4 mr-1.5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Admin Panel
                            </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="block border-t border-dark-700">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-dark-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
    <div class="bg-green-600 text-white px-4 py-3">
        <div class="container mx-auto">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-600 text-white px-4 py-3">
        <div class="container mx-auto">
            {{ session('error') }}
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Cookie Consent Banner -->
    <div id="cookie-consent" class="hidden fixed bottom-0 left-0 right-0 bg-dark-900 border-t border-dark-700 p-4 z-50">
        <div class="container mx-auto flex items-center justify-between">
            <p class="text-sm text-dark-300">
                This site uses minimal cookies to enhance your experience. By continuing to use this site, you accept our use of cookies.
            </p>
            <button onclick="acceptCookies()" class="btn-primary">
                Accept
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark-900 border-t border-dark-700 mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center text-dark-400">
                <p>&copy; {{ date('Y') }} WatchTheFlix. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Toast Notifications -->
    <x-toast />
</body>
</html>
