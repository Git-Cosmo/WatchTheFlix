@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card p-6">
            <div class="text-4xl font-bold text-accent-500 mb-2">{{ $stats['total_users'] }}</div>
            <div class="text-dark-400">Total Users</div>
        </div>
        <div class="card p-6">
            <div class="text-4xl font-bold text-accent-500 mb-2">{{ $stats['total_media'] }}</div>
            <div class="text-dark-400">Total Media</div>
        </div>
        <div class="card p-6">
            <div class="text-4xl font-bold text-accent-500 mb-2">{{ $stats['active_invites'] }}</div>
            <div class="text-dark-400">Active Invites</div>
        </div>
        <div class="card p-6">
            <div class="text-4xl font-bold text-accent-500 mb-2">{{ $stats['total_views'] }}</div>
            <div class="text-dark-400">Total Views</div>
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
        <a href="{{ route('forum.index') }}" class="btn-secondary text-center" target="_blank">View Forum</a>
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
