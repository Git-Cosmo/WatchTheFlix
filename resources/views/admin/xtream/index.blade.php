@extends('layouts.app')

@section('title', 'Xtream Codes Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-xtream-hold-notice />

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">Xtream Codes API Management</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.xtream.configuration') }}" class="btn-secondary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Configuration
            </a>
            <a href="{{ route('admin.xtream.documentation') }}" class="btn-secondary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Documentation
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="card p-6 hover:border-accent-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="text-4xl font-bold text-accent-500">{{ $stats['total_channels'] }}</div>
                <div class="w-12 h-12 bg-accent-500/10 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                    </svg>
                </div>
            </div>
            <div class="text-dark-400 text-sm">Live TV Channels</div>
            <div class="mt-2 text-xs text-dark-500">
                {{ $stats['active_channels'] }} active
            </div>
        </div>

        <div class="card p-6 hover:border-accent-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="text-4xl font-bold text-accent-500">{{ $stats['total_vod'] }}</div>
                <div class="w-12 h-12 bg-accent-500/10 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-dark-400 text-sm">VOD Content</div>
            <div class="mt-2 text-xs text-dark-500">
                {{ $stats['published_vod'] }} published
            </div>
        </div>

        <div class="card p-6 hover:border-accent-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <div class="text-4xl font-bold text-accent-500">{{ $stats['users_with_tokens'] }}</div>
                <div class="w-12 h-12 bg-accent-500/10 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
            </div>
            <div class="text-dark-400 text-sm">Active API Users</div>
            <div class="mt-2 text-xs text-dark-500">
                {{ $stats['total_users'] }} total users
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.xtream.streams') }}" class="btn-secondary text-center">
            <svg class="h-5 w-5 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Manage Streams
        </a>
        <a href="{{ route('admin.xtream.users') }}" class="btn-secondary text-center">
            <svg class="h-5 w-5 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            API Users
        </a>
        <a href="{{ route('admin.xtream.statistics') }}" class="btn-secondary text-center">
            <svg class="h-5 w-5 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Statistics
        </a>
        <a href="{{ route('admin.xtream.export-epg') }}" class="btn-secondary text-center">
            <svg class="h-5 w-5 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export EPG
        </a>
    </div>

    <!-- Recent API Activity -->
    <div class="card p-6">
        <h2 class="text-xl font-semibold mb-4">Recent API Token Activity</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-700">
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">User</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Token Name</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Created</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Last Used</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTokens as $token)
                    <tr class="border-b border-dark-800">
                        <td class="py-3 px-4">
                            {{ $recentUsers[$token->tokenable_id]->name ?? 'Unknown' }}
                            <br>
                            <span class="text-xs text-dark-500">{{ $recentUsers[$token->tokenable_id]->email ?? '' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-accent-500/20 text-accent-400 text-xs rounded">{{ $token->name }}</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-dark-400">
                            {{ \Carbon\Carbon::parse($token->created_at)->diffForHumans() }}
                        </td>
                        <td class="py-3 px-4 text-sm text-dark-400">
                            {{ $token->last_used_at ? \Carbon\Carbon::parse($token->last_used_at)->diffForHumans() : 'Never' }}
                        </td>
                        <td class="py-3 px-4">
                            <form method="POST" action="{{ route('admin.xtream.revoke-token', $token->tokenable_id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 text-sm" onclick="return confirm('Revoke this token?')">
                                    Revoke
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-dark-400">
                            No API tokens generated yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
