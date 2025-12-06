@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">User Details</h1>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Back to Users</a>
        </div>

        <div class="card p-8 mb-6">
            <div class="flex items-center space-x-6 mb-8">
                <div class="w-24 h-24 rounded-full bg-accent-500 flex items-center justify-center text-3xl font-bold">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-dark-400">{{ $user->email }}</p>
                    <div class="mt-2 flex space-x-2">
                        @foreach($user->roles as $role)
                        <span class="px-3 py-1 rounded text-sm {{ $role->name === 'admin' ? 'bg-accent-900 text-accent-300' : 'bg-dark-700 text-dark-300' }}">
                            {{ ucfirst($role->name) }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($user->bio)
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Bio</h3>
                <p class="text-dark-300">{{ $user->bio }}</p>
            </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="text-center p-4 bg-dark-800 rounded-lg">
                    <div class="text-2xl font-bold text-accent-500">{{ $user->watchlist_count }}</div>
                    <div class="text-sm text-dark-400">Watchlist</div>
                </div>
                <div class="text-center p-4 bg-dark-800 rounded-lg">
                    <div class="text-2xl font-bold text-accent-500">{{ $user->favorites_count }}</div>
                    <div class="text-sm text-dark-400">Favorites</div>
                </div>
                <div class="text-center p-4 bg-dark-800 rounded-lg">
                    <div class="text-2xl font-bold text-accent-500">{{ $user->ratings_count }}</div>
                    <div class="text-sm text-dark-400">Ratings</div>
                </div>
                <div class="text-center p-4 bg-dark-800 rounded-lg">
                    <div class="text-2xl font-bold text-accent-500">{{ $user->comments_count }}</div>
                    <div class="text-sm text-dark-400">Comments</div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-dark-700">
                    <span class="text-dark-400">Real-Debrid Status</span>
                    <span class="{{ $user->real_debrid_enabled ? 'text-green-500' : 'text-dark-500' }}">
                        {{ $user->real_debrid_enabled ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-dark-700">
                    <span class="text-dark-400">Parental Controls</span>
                    <span class="{{ $user->parental_control_enabled ? 'text-green-500' : 'text-dark-500' }}">
                        {{ $user->parental_control_enabled ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-dark-700">
                    <span class="text-dark-400">Member Since</span>
                    <span>{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-dark-400">Last Updated</span>
                    <span>{{ $user->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
