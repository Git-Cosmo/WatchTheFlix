@extends('layouts.admin')

@section('title', 'Manage Media')

@section('content')
<div class="max-w-7xl">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Manage Media</h1>
                <p class="text-dark-400 mt-1">View and manage all media content in your library</p>
            </div>
            <a href="{{ route('admin.media.create') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Media
            </a>
        </div>
    </div>

    <!-- Media Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gh-bg-tertiary">
                    <tr class="border-b border-gh-border">
                        <th class="text-left px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Title</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Type</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Year</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Status</th>
                        <th class="text-right px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gh-border">
                    @forelse($media as $item)
                    <tr class="hover:bg-gh-bg-tertiary/50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-3">
                                @if($item->poster_url)
                                <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-12 h-16 object-cover rounded shadow-sm">
                                @else
                                <div class="w-12 h-16 bg-dark-800 rounded flex items-center justify-center text-2xl">
                                    🎬
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-white truncate">{{ $item->title }}</div>
                                    <div class="text-sm text-dark-400 line-clamp-2 mt-1">{{ Str::limit($item->description, 80) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-accent-500/10 text-accent-400">
                                {{ ucfirst($item->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-dark-300">{{ $item->release_year ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $item->is_published ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $item->is_published ? 'bg-green-400' : 'bg-red-400' }} mr-1.5"></span>
                                {{ $item->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('media.show', $item) }}" target="_blank" class="text-dark-400 hover:text-accent-400 transition-colors" title="View">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.media.edit', $item) }}" class="text-accent-500 hover:text-accent-400 transition-colors font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.media.destroy', $item) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400 transition-colors font-medium" onclick="return confirm('Are you sure you want to delete this media?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="h-12 w-12 text-dark-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                                </svg>
                                <p class="text-dark-400 text-lg">No media found</p>
                                <a href="{{ route('admin.media.create') }}" class="btn-primary text-sm">Add your first media</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($media->hasPages())
    <div class="mt-6">
        {{ $media->links() }}
    </div>
    @endif
</div>
@endsection
