@extends('layouts.admin')

@section('title', 'TV Program Management')

@section('content')
<div class="max-w-7xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">TV Program Management</h1>
            <p class="text-dark-400 mt-2">Manage EPG (Electronic Program Guide) data for TV channels</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.tv-programs.create') }}" class="btn-primary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Program
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="card p-6">
            <div class="text-3xl font-bold text-accent-400 mb-2">{{ number_format($stats['total_programs']) }}</div>
            <div class="text-sm text-dark-400">Total Programs</div>
        </div>
        <div class="card p-6">
            <div class="text-3xl font-bold text-green-400 mb-2">{{ number_format($stats['current_programs']) }}</div>
            <div class="text-sm text-dark-400">Currently Airing</div>
        </div>
        <div class="card p-6">
            <div class="text-3xl font-bold text-blue-400 mb-2">{{ number_format($stats['upcoming_programs']) }}</div>
            <div class="text-sm text-dark-400">Upcoming Programs</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6 mb-6">
        <form method="GET" action="{{ route('admin.tv-programs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Channel</label>
                <select name="channel_id" class="input-field">
                    <option value="">All Channels</option>
                    @foreach($channels as $channel)
                        <option value="{{ $channel->id }}" {{ request('channel_id') == $channel->id ? 'selected' : '' }}>
                            {{ $channel->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Genre</label>
                <select name="genre" class="input-field">
                    <option value="">All Genres</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                            {{ ucfirst($genre) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-field">
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="btn-primary">
                    Apply Filters
                </button>
                <a href="{{ route('admin.tv-programs.index') }}" class="btn-secondary">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="card p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Bulk Actions</h3>
        <form method="POST" action="{{ route('admin.tv-programs.bulk-delete-old') }}" class="flex items-end gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Delete programs older than</label>
                <input type="number" name="days" value="30" min="1" max="365" class="input-field w-32">
            </div>
            <div>
                <button type="submit" class="btn-danger" onclick="return confirm('Are you sure you want to delete old programs?')">
                    Delete Old Programs
                </button>
            </div>
        </form>
    </div>

    <!-- Programs Table -->
    <div class="card">
        <div class="p-6 border-b border-dark-700">
            <h2 class="text-xl font-semibold text-white">TV Programs</h2>
        </div>

        @if($programs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-dark-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">Program</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">Channel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">Start Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">End Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">Genre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-dark-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-dark-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-700">
                        @foreach($programs as $program)
                            <tr class="hover:bg-dark-800/50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-white">{{ $program->title }}</div>
                                    @if($program->description)
                                        <div class="text-sm text-dark-400 truncate max-w-md">{{ \Illuminate\Support\Str::limit($program->description, 100) }}</div>
                                    @endif
                                    @if($program->is_premiere)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-900/50 text-yellow-300">
                                            Premiere
                                        </span>
                                    @endif
                                    @if($program->is_repeat)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-300">
                                            Repeat
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-white">{{ $program->channel->name }}</div>
                                    <div class="text-xs text-dark-400">{{ $program->channel->country }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-dark-300">
                                    {{ $program->start_time->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-dark-300">
                                    {{ $program->end_time->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-dark-300">
                                    {{ $program->genre ? ucfirst($program->genre) : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($program->isCurrentlyAiring())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-900/50 text-green-300">
                                            ● On Air
                                        </span>
                                    @elseif($program->start_time->isFuture())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-900/50 text-blue-300">
                                            Upcoming
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-400">
                                            Ended
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.tv-programs.edit', $program) }}" class="text-accent-400 hover:text-accent-300">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.tv-programs.destroy', $program) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-dark-700">
                {{ $programs->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="text-6xl mb-4">📺</div>
                <h3 class="text-xl font-semibold text-white mb-2">No programs found</h3>
                <p class="text-dark-400 mb-6">Add TV programs manually or sync from EPG providers.</p>
                <a href="{{ route('admin.tv-programs.create') }}" class="btn-primary">
                    Add First Program
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
