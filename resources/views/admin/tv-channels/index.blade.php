@extends('layouts.admin')

@section('title', 'TV Channel Management')

@section('content')
<div class="max-w-7xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">TV Channel Management</h1>
            <p class="text-dark-400 mt-2">Manage TV channels and sync from IPTV-ORG API or EPG providers</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.tv-programs.index') }}" class="btn-secondary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Manage Programs
            </a>
            <a href="{{ route('admin.tv-channels.create') }}" class="btn-primary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Channel
            </a>
        </div>
    </div>

    <!-- IPTV-ORG Sync Actions -->
    <div class="card p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">IPTV-ORG API Sync</h3>
        <p class="text-dark-400 mb-4">Sync channels and EPG data from the IPTV-ORG database (642 UK channels + popular US channels)</p>
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.tv-channels.sync-iptv-channels') }}" class="inline">
                @csrf
                <button type="submit" class="btn-primary" onclick="return confirm('This will sync UK channels and popular US channels from IPTV-ORG. Continue?')">
                    <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Sync Channels from IPTV-ORG
                </button>
            </form>
            <form method="POST" action="{{ route('admin.tv-channels.sync-iptv-epg') }}" class="inline">
                @csrf
                <button type="submit" class="btn-secondary" onclick="return confirm('This will attempt to sync EPG guides for IPTV-ORG channels. Continue?')">
                    <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Sync EPG Guides
                </button>
            </form>
            <form method="POST" action="{{ route('admin.tv-channels.sync') }}" class="inline">
                @csrf
                <button type="submit" class="btn-secondary">
                    <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Sync EPG (XMLTV)
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="card p-6">
            <div class="text-3xl font-bold text-accent-400 mb-2">{{ $stats['total_channels'] }}</div>
            <div class="text-sm text-dark-400">Total Channels</div>
        </div>
        <div class="card p-6">
            <div class="text-3xl font-bold text-blue-400 mb-2">{{ $stats['uk_channels'] }}</div>
            <div class="text-sm text-dark-400">UK Channels</div>
        </div>
        <div class="card p-6">
            <div class="text-3xl font-bold text-red-400 mb-2">{{ $stats['us_channels'] }}</div>
            <div class="text-sm text-dark-400">US Channels</div>
        </div>
        <div class="card p-6">
            <div class="text-3xl font-bold text-green-400 mb-2">{{ $stats['active_channels'] }}</div>
            <div class="text-sm text-dark-400">Active Channels</div>
        </div>
        <div class="card p-6">
            <div class="text-3xl font-bold text-purple-400 mb-2">{{ number_format($stats['total_programs']) }}</div>
            <div class="text-sm text-dark-400">Total Programs</div>
        </div>
    </div>

    <!-- Channels Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gh-bg-tertiary border-b border-gh-border">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">Channel</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">Country</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">Channel ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">Programs</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-dark-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gh-border">
                    @forelse($channels as $channel)
                    <tr class="hover:bg-gh-bg-tertiary/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-dark-300">{{ $channel->channel_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($channel->logo_url)
                                <img src="{{ $channel->logo_url }}" alt="{{ $channel->name }}" class="w-10 h-10 rounded object-cover mr-3">
                                @else
                                <div class="w-10 h-10 bg-accent-500/10 rounded flex items-center justify-center mr-3">
                                    <svg class="h-6 w-6 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-white">{{ $channel->name }}</div>
                                    @if($channel->description)
                                    <div class="text-xs text-dark-400 max-w-xs truncate">{{ $channel->description }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded {{ $channel->country === 'UK' ? 'bg-blue-500/20 text-blue-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $channel->country }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-dark-400 font-mono">
                            {{ $channel->channel_id ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-dark-300">
                            {{ $channel->programs_count }} programs
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($channel->is_active)
                            <span class="px-2 py-1 text-xs font-medium rounded bg-green-500/20 text-green-400">Active</span>
                            @else
                            <span class="px-2 py-1 text-xs font-medium rounded bg-gray-500/20 text-gray-400">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.tv-channels.edit', $channel) }}" class="text-accent-400 hover:text-accent-300">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.tv-channels.destroy', $channel) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this channel and all its programs?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-dark-500">
                                <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <p class="text-lg">No TV channels found</p>
                                <p class="mt-2 text-sm">Add channels manually or sync from EPG provider</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($channels->hasPages())
    <div class="mt-6">
        {{ $channels->links() }}
    </div>
    @endif
</div>
@endsection
