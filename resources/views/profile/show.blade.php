@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="card p-8">
            <div class="flex items-center space-x-6 mb-8">
                <div class="w-24 h-24 rounded-full bg-accent-500 flex items-center justify-center text-3xl font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    <p class="text-dark-400">{{ $user->email }}</p>
                    @if($user->real_debrid_enabled)
                    <span class="inline-block mt-2 px-3 py-1 bg-accent-900 text-accent-300 rounded-full text-sm">
                        Real-Debrid Enabled
                    </span>
                    @endif
                </div>
            </div>

            @if($user->bio)
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-2">Bio</h2>
                <p class="text-dark-300">{{ $user->bio }}</p>
            </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="text-center">
                    <div class="text-2xl font-bold text-accent-500">{{ $user->watchlist_count }}</div>
                    <div class="text-sm text-dark-400">Watchlist</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-accent-500">{{ $user->favorites_count }}</div>
                    <div class="text-sm text-dark-400">Favorites</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-accent-500">{{ $user->ratings_count }}</div>
                    <div class="text-sm text-dark-400">Ratings</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-accent-500">{{ $user->comments_count }}</div>
                    <div class="text-sm text-dark-400">Comments</div>
                </div>
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('profile.settings') }}" class="btn-primary">Edit Settings</a>
            </div>
        </div>
    </div>
</div>
@endsection
