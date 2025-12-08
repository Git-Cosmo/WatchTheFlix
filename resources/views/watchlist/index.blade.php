@extends('layouts.app')

@section('title', 'My Watchlist')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Watchlist</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($watchlist as $item)
        <div class="card overflow-hidden">
            <a href="{{ $item->getRouteUrl() }}">
                @if($item->poster_url)
                <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="w-full h-64 object-cover lazy" loading="lazy">
                @else
                <div class="w-full h-64 bg-dark-800 flex items-center justify-center">
                    <span class="text-dark-500 text-4xl">🎬</span>
                </div>
                @endif
            </a>
            <div class="p-3">
                <h3 class="text-sm font-semibold truncate">{{ $item->title }}</h3>
                <p class="text-xs text-dark-400">{{ $item->release_year }}</p>
                <form method="POST" action="{{ route('watchlist.remove', $item) }}" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 hover:text-red-400">Remove</button>
                </form>
            </div>
        </div>
        @empty
        <x-empty-state 
            icon="📺" 
            title="Your watchlist is empty" 
            message="Start adding movies and TV shows to keep track of what you want to watch."
            action-text="Browse Content"
            action-url="{{ route('media.index') }}"
            class="col-span-full"
        />
        @endforelse
    </div>

    <div class="mt-8">
        {{ $watchlist->links() }}
    </div>
</div>
@endsection
