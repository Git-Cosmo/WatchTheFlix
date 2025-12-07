@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Profile Header Card -->
        <div class="card p-8 mb-8">
            <div class="flex items-start justify-between mb-8">
                <div class="flex items-center space-x-6">
                    @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-accent-500">
                    @else
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center text-3xl font-bold text-white shadow-lg">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    @endif
                    <div>
                        <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                        <p class="text-gh-text-muted">{{ $user->email }}</p>
                        @if($user->real_debrid_enabled)
                        <span class="inline-block mt-2 px-3 py-1 bg-accent-500/20 text-accent-400 rounded-full text-sm font-medium">
                            <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Real-Debrid Enabled
                        </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('profile.settings') }}" class="btn-primary">
                    <svg class="h-4 w-4 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Profile
                </a>
            </div>

            @if($user->bio)
            <div class="mb-8 pb-8 border-b border-gh-border">
                <h2 class="text-lg font-semibold mb-3 text-gh-text-muted">About</h2>
                <p class="text-dark-300 leading-relaxed">{{ $user->bio }}</p>
            </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-gh-bg rounded-lg border border-gh-border hover:border-accent-500/50 transition-all">
                    <div class="text-3xl font-bold text-accent-400 mb-1">{{ $user->watchlist_count }}</div>
                    <div class="text-sm text-gh-text-muted">Watchlist</div>
                </div>
                <div class="text-center p-4 bg-gh-bg rounded-lg border border-gh-border hover:border-accent-500/50 transition-all">
                    <div class="text-3xl font-bold text-accent-400 mb-1">{{ $user->favorites_count }}</div>
                    <div class="text-sm text-gh-text-muted">Favorites</div>
                </div>
                <div class="text-center p-4 bg-gh-bg rounded-lg border border-gh-border hover:border-accent-500/50 transition-all">
                    <div class="text-3xl font-bold text-accent-400 mb-1">{{ $user->ratings_count }}</div>
                    <div class="text-sm text-gh-text-muted">Ratings</div>
                </div>
                <div class="text-center p-4 bg-gh-bg rounded-lg border border-gh-border hover:border-accent-500/50 transition-all">
                    <div class="text-3xl font-bold text-accent-400 mb-1">{{ $user->comments_count }}</div>
                    <div class="text-sm text-gh-text-muted">Comments</div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="card p-8">
            <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>
            
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/50 rounded-lg text-green-400">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Avatar Upload -->
                <div>
                    <label class="block text-sm font-medium text-gh-text-muted mb-3">Profile Picture</label>
                    <div class="flex items-center space-x-6">
                        @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover">
                        @else
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center text-2xl font-bold text-white">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="input-field">
                            <p class="mt-2 text-sm text-gh-text-muted">JPG, PNG or GIF. Max size 2MB.</p>
                            @error('avatar')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gh-text-muted mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="input-field w-full @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gh-text-muted mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="input-field w-full @error('email') border-red-500 @enderror">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gh-text-muted mb-2">Bio</label>
                    <textarea name="bio" id="bio" rows="4" maxlength="500" class="input-field w-full @error('bio') border-red-500 @enderror" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    <p class="mt-2 text-sm text-gh-text-muted">Maximum 500 characters</p>
                    @error('bio')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="btn-primary">
                        <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
