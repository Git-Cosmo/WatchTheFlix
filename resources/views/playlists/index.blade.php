@extends('layouts.app')

@section('title', 'My Playlists')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">My Playlists</h1>
        <a href="{{ route('playlists.create') }}" class="btn-primary">
            <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Playlist
        </a>
    </div>

    @if($playlists->isEmpty())
    <div class="text-center py-20">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gh-bg-secondary rounded-full mb-6">
            <svg class="h-10 w-10 text-gh-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
            </svg>
        </div>
        <p class="text-gh-text-muted text-lg mb-4">You haven't created any playlists yet</p>
        <a href="{{ route('playlists.create') }}" class="btn-primary">Create Your First Playlist</a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($playlists as $playlist)
        <div class="card p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="text-xl font-bold mb-2">
                        <a href="{{ route('playlists.show', $playlist) }}" class="hover:text-accent-400 transition-colors">
                            {{ $playlist->name }}
                        </a>
                    </h3>
                    @if($playlist->description)
                    <p class="text-dark-300 text-sm line-clamp-2">{{ $playlist->description }}</p>
                    @endif
                </div>
                <span class="ml-2 px-2 py-1 text-xs rounded {{ $playlist->is_public ? 'bg-green-500/20 text-green-400' : 'bg-dark-700 text-dark-300' }}">
                    {{ $playlist->is_public ? 'Public' : 'Private' }}
                </span>
            </div>

            <div class="flex items-center justify-between text-sm text-dark-400 mb-4">
                <span>{{ $playlist->media_count ?? 0 }} {{ ($playlist->media_count ?? 0) === 1 ? 'item' : 'items' }}</span>
                <span>{{ $playlist->created_at->diffForHumans() }}</span>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('playlists.show', $playlist) }}" class="btn-secondary flex-1 text-center">View</a>
                <a href="{{ route('playlists.edit', $playlist) }}" class="btn-secondary">Edit</a>
                <form method="POST" action="{{ route('playlists.destroy', $playlist) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary text-red-400 hover:text-red-300" onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
