@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card p-6 hover:border-accent-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="text-4xl font-bold text-accent-500">{{ $stats['total_users'] }}</div>
                <div class="w-12 h-12 bg-accent-500/10 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-dark-400 text-sm">Total Users</div>
        </div>
        <div class="card p-6 hover:border-accent-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="text-4xl font-bold text-accent-500">{{ $stats['total_media'] }}</div>
                <div class="w-12 h-12 bg-accent-500/10 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                </div>
            </div>
            <div class="text-dark-400 text-sm">Total Media</div>
            <div class="mt-2 text-xs text-dark-500">
                {{ $stats['published_media'] }} published • {{ $stats['draft_media'] }} draft
            </div>
        </div>
        <div class="card p-6 hover:border-accent-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="text-4xl font-bold text-accent-500">{{ $stats['active_invites'] }}</div>
                <div class="w-12 h-12 bg-accent-500/10 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-dark-400 text-sm">Active Invites</div>
        </div>
        <div class="card p-6 hover:border-accent-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="text-4xl font-bold text-accent-500">{{ $stats['movies_count'] + $stats['series_count'] }}</div>
                <div class="w-12 h-12 bg-accent-500/10 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-dark-400 text-sm">Content Library</div>
            <div class="mt-2 text-xs text-dark-500">
                {{ $stats['movies_count'] }} movies • {{ $stats['series_count'] }} series
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.media.create') }}" class="btn-primary text-center">Add Media</a>
        <a href="{{ route('admin.invites.index') }}" class="btn-secondary text-center">Manage Invites</a>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary text-center">Manage Users</a>
        <a href="{{ route('admin.forum.admin.index') }}" class="btn-secondary text-center">Manage Forum</a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.settings.index') }}" class="btn-secondary text-center">Settings</a>
        <a href="{{ route('admin.tmdb-import.index') }}" class="btn-secondary text-center">TMDB Bulk Import</a>
        <a href="{{ route('forum.index') }}" class="btn-secondary text-center" target="_blank">View Forum</a>
    </div>

    <!-- User Engagement Metrics -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">User Engagement</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
            <div class="card p-4">
                <div class="text-2xl font-bold text-accent-400">{{ $engagement['total_comments'] }}</div>
                <div class="text-sm text-dark-400">Comments</div>
            </div>
            <div class="card p-4">
                <div class="text-2xl font-bold text-accent-400">{{ $engagement['total_ratings'] }}</div>
                <div class="text-sm text-dark-400">Ratings</div>
            </div>
            <div class="card p-4">
                <div class="text-2xl font-bold text-accent-400">{{ $engagement['total_favorites'] }}</div>
                <div class="text-sm text-dark-400">Favorites</div>
            </div>
            <div class="card p-4">
                <div class="text-2xl font-bold text-accent-400">{{ $engagement['total_watchlist_items'] }}</div>
                <div class="text-sm text-dark-400">Watchlist Items</div>
            </div>
            <div class="card p-4">
                <div class="text-2xl font-bold text-accent-400">{{ $engagement['forum_threads'] }}</div>
                <div class="text-sm text-dark-400">Forum Threads</div>
            </div>
            <div class="card p-4">
                <div class="text-2xl font-bold text-accent-400">{{ $engagement['forum_posts'] }}</div>
                <div class="text-sm text-dark-400">Forum Posts</div>
            </div>
            <div class="card p-4">
                <div class="text-2xl font-bold text-accent-400">{{ $engagement['total_playlists'] }}</div>
                <div class="text-sm text-dark-400">Playlists</div>
            </div>
            <div class="card p-4">
                <div class="text-2xl font-bold text-accent-400">{{ $engagement['total_reactions'] }}</div>
                <div class="text-sm text-dark-400">Reactions</div>
            </div>
        </div>
    </div>

    <!-- Growth Metrics -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">30-Day Growth</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card p-4 border-l-4 border-green-500">
                <div class="text-2xl font-bold text-green-400">+{{ $growth['new_users'] }}</div>
                <div class="text-sm text-dark-400">New Users</div>
            </div>
            <div class="card p-4 border-l-4 border-blue-500">
                <div class="text-2xl font-bold text-blue-400">+{{ $growth['new_media'] }}</div>
                <div class="text-sm text-dark-400">New Media</div>
            </div>
            <div class="card p-4 border-l-4 border-purple-500">
                <div class="text-2xl font-bold text-purple-400">+{{ $growth['new_comments'] }}</div>
                <div class="text-sm text-dark-400">New Comments</div>
            </div>
            <div class="card p-4 border-l-4 border-yellow-500">
                <div class="text-2xl font-bold text-yellow-400">+{{ $growth['new_ratings'] }}</div>
                <div class="text-sm text-dark-400">New Ratings</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Top Rated Media -->
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-4">Top Rated Content</h2>
            <div class="space-y-3">
                @forelse($topRated as $media)
                <div class="flex items-center justify-between border-b border-dark-700 pb-3">
                    <div class="flex-1">
                        <p class="font-medium">{{ $media->title }}</p>
                        <p class="text-xs text-dark-400">{{ $media->ratings_count }} ratings</p>
                    </div>
                    <span class="text-lg font-bold text-accent-400">
                        {{ number_format($media->ratings_avg_rating, 1) }}/10
                    </span>
                </div>
                @empty
                <p class="text-dark-400">No rated content yet</p>
                @endforelse
            </div>
        </div>

        <!-- Most Viewed Media -->
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-4">Most Viewed</h2>
            <div class="space-y-3">
                @forelse($mostViewed as $media)
                <div class="flex items-center justify-between border-b border-dark-700 pb-3">
                    <div class="flex-1">
                        <p class="font-medium">{{ $media->title }}</p>
                        <p class="text-xs text-dark-400">{{ $media->type }}</p>
                    </div>
                    <span class="text-lg font-bold text-accent-400">
                        {{ $media->viewing_history_count }} views
                    </span>
                </div>
                @empty
                <p class="text-dark-400">No viewing history yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Platform Statistics -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Platform Distribution</h2>
        <div class="card p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @forelse($platformStats as $platform)
                <div class="text-center">
                    <div class="text-2xl font-bold text-accent-400">{{ $platform->media_count }}</div>
                    <div class="text-sm text-dark-400">{{ $platform->name }}</div>
                </div>
                @empty
                <p class="text-dark-400 col-span-full">No platform data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Activity -->
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
            <div class="space-y-3">
                @forelse($recentActivity as $activity)
                <div class="border-b border-dark-700 pb-3">
                    <p class="text-sm">{{ $activity->description }}</p>
                    <p class="text-xs text-dark-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-dark-400">No recent activity</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Users</h2>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                <div class="flex items-center justify-between border-b border-dark-700 pb-3">
                    <div>
                        <p class="font-medium">{{ $user->name }}</p>
                        <p class="text-sm text-dark-400">{{ $user->email }}</p>
                    </div>
                    <span class="text-xs text-dark-400">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-dark-400">No users yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
