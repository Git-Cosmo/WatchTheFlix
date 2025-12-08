<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WatchTheFlix') }} - Admin - @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-dark-950 text-dark-100">
    <!-- Top Navigation Bar -->
    <nav class="bg-gh-bg-secondary border-b border-gh-border sticky top-0 z-50 backdrop-blur-sm bg-gh-bg-secondary/95">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo and Back to Site -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-xl font-bold bg-gradient-to-r from-accent-400 to-accent-600 bg-clip-text text-transparent hover:from-accent-300 hover:to-accent-500 transition-all">
                        WatchTheFlix
                    </a>
                    <span class="text-dark-500">|</span>
                    <a href="{{ route('home') }}" class="text-sm text-dark-400 hover:text-dark-200 flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Site
                    </a>
                </div>

                <!-- Right Side: User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- User Dropdown -->
                    <div class="relative">
                        <button onclick="toggleDropdown('admin-user-menu')" class="flex items-center space-x-2 text-dark-300 hover:text-dark-100">
                            @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border-2 border-accent-500">
                            @else
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center text-sm font-medium text-white">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            @endif
                            <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div id="admin-user-menu" class="hidden absolute right-0 mt-2 w-48 bg-dark-800 border border-dark-700 rounded-lg shadow-lg z-10">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-dark-100">Profile</a>
                            <a href="{{ route('profile.settings') }}" class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-dark-100">Settings</a>
                            <form method="POST" action="{{ route('logout') }}" class="block border-t border-dark-700">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-dark-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="flex min-h-[calc(100vh-4rem)]">
        <!-- Left Sidebar -->
        <aside class="w-64 bg-gh-bg-secondary border-r border-gh-border flex-shrink-0">
            <nav class="px-3 py-6 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'admin-nav-link-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Media Management -->
                <a href="{{ route('admin.media.index') }}" class="admin-nav-link {{ request()->routeIs('admin.media.*') ? 'admin-nav-link-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                    <span>Media</span>
                </a>

                <!-- TMDB Import -->
                <a href="{{ route('admin.tmdb-import.index') }}" class="admin-nav-link {{ request()->routeIs('admin.tmdb-import.*') ? 'admin-nav-link-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    <span>TMDB Import</span>
                </a>

                <!-- TV Channels -->
                <a href="{{ route('admin.tv-channels.index') }}" class="admin-nav-link {{ request()->routeIs('admin.tv-channels.*') ? 'admin-nav-link-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span>TV Channels</span>
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'admin-nav-link-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Users</span>
                </a>

                <!-- Invites -->
                <a href="{{ route('admin.invites.index') }}" class="admin-nav-link {{ request()->routeIs('admin.invites.*') ? 'admin-nav-link-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Invites</span>
                </a>

                <!-- Forum Management -->
                <a href="{{ route('admin.forum.admin.index') }}" class="admin-nav-link {{ request()->routeIs('admin.forum.*') ? 'admin-nav-link-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                    <span>Forum</span>
                </a>

                <!-- Analytics -->
                <a href="{{ route('admin.analytics.index') }}" class="admin-nav-link {{ request()->routeIs('admin.analytics.*') ? 'admin-nav-link-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Analytics</span>
                </a>

                <!-- Activity Log -->
                <a href="{{ route('admin.activity-log.index') }}" class="admin-nav-link {{ request()->routeIs('admin.activity-log.*') ? 'admin-nav-link-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Activity Log</span>
                </a>

                <div class="my-4 border-t border-gh-border"></div>

                <!-- Xtream Codes (On Hold) -->
                <a href="{{ route('admin.xtream.index') }}" class="admin-nav-link {{ request()->routeIs('admin.xtream.*') ? 'admin-nav-link-active' : '' }} opacity-60">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span>Xtream Codes</span>
                    <span class="ml-auto text-xs px-2 py-0.5 bg-yellow-500/20 text-yellow-400 rounded">Hold</span>
                </a>

                <div class="my-4 border-t border-gh-border"></div>

                <!-- Settings -->
                <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'admin-nav-link-active' : '' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-auto">
            <!-- Flash Messages -->
            @if (session('success'))
            <div class="bg-green-600 text-white px-6 py-3 m-4 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-600 text-white px-6 py-3 m-4 rounded-lg">
                {{ session('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="bg-red-600 text-white px-6 py-3 m-4 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Page Content -->
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- JavaScript for dropdowns -->
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle('hidden');
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function closeDropdown(e) {
                if (!e.target.closest(`#${id}`) && !e.target.closest('button')) {
                    dropdown.classList.add('hidden');
                    document.removeEventListener('click', closeDropdown);
                }
            });
        }
    </script>
</body>
</html>
