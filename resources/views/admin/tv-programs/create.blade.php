@extends('layouts.admin')

@section('title', 'Add TV Program')

@section('content')
<div class="max-w-3xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Add TV Program</h1>
        <p class="text-dark-400 mt-2">Create a new EPG program entry</p>
    </div>

    <form method="POST" action="{{ route('admin.tv-programs.store') }}" class="card p-6">
        @csrf

        <div class="space-y-6">
            <!-- Channel -->
            <div>
                <label for="tv_channel_id" class="block text-sm font-medium text-dark-300 mb-2">Channel *</label>
                <select name="tv_channel_id" id="tv_channel_id" required class="input-field w-full">
                    <option value="">Select Channel</option>
                    @foreach($channels as $channel)
                        <option value="{{ $channel->id }}" {{ old('tv_channel_id') == $channel->id ? 'selected' : '' }}>
                            {{ $channel->name }} ({{ $channel->country }})
                        </option>
                    @endforeach
                </select>
                @error('tv_channel_id')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-dark-300 mb-2">Program Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="input-field w-full" placeholder="News at 6">
                @error('title')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-dark-300 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" class="input-field w-full" placeholder="Program description">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-dark-300 mb-2">Start Time *</label>
                    <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" required class="input-field w-full">
                    @error('start_time')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-dark-300 mb-2">End Time *</label>
                    <input type="datetime-local" name="end_time" id="end_time" value="{{ old('end_time') }}" required class="input-field w-full">
                    @error('end_time')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Genre -->
                <div>
                    <label for="genre" class="block text-sm font-medium text-dark-300 mb-2">Genre</label>
                    <input type="text" name="genre" id="genre" value="{{ old('genre') }}" class="input-field w-full" placeholder="News">
                    @error('genre')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rating -->
                <div>
                    <label for="rating" class="block text-sm font-medium text-dark-300 mb-2">Rating</label>
                    <input type="text" name="rating" id="rating" value="{{ old('rating') }}" class="input-field w-full" placeholder="PG">
                    @error('rating')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Image URL -->
            <div>
                <label for="image_url" class="block text-sm font-medium text-dark-300 mb-2">Image URL</label>
                <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" class="input-field w-full" placeholder="https://example.com/program.jpg">
                @error('image_url')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Series Information -->
            <div class="border border-dark-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-white mb-4">Series Information (Optional)</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="series_id" class="block text-sm font-medium text-dark-300 mb-2">Series ID</label>
                        <input type="text" name="series_id" id="series_id" value="{{ old('series_id') }}" class="input-field w-full" placeholder="series-123">
                        @error('series_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="season_number" class="block text-sm font-medium text-dark-300 mb-2">Season Number</label>
                            <input type="number" name="season_number" id="season_number" value="{{ old('season_number') }}" min="1" class="input-field w-full" placeholder="1">
                            @error('season_number')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="episode_number" class="block text-sm font-medium text-dark-300 mb-2">Episode Number</label>
                            <input type="number" name="episode_number" id="episode_number" value="{{ old('episode_number') }}" min="1" class="input-field w-full" placeholder="1">
                            @error('episode_number')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flags -->
            <div class="space-y-3">
                <div class="flex items-center">
                    <input type="checkbox" name="is_repeat" id="is_repeat" value="1" {{ old('is_repeat') ? 'checked' : '' }} class="h-4 w-4 text-accent-500 focus:ring-accent-500 border-dark-600 rounded bg-dark-700">
                    <label for="is_repeat" class="ml-2 block text-sm text-dark-300">
                        This is a repeat broadcast
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_premiere" id="is_premiere" value="1" {{ old('is_premiere') ? 'checked' : '' }} class="h-4 w-4 text-accent-500 focus:ring-accent-500 border-dark-600 rounded bg-dark-700">
                    <label for="is_premiere" class="ml-2 block text-sm text-dark-300">
                        This is a premiere broadcast
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-6 border-t border-dark-700">
                <button type="submit" class="btn-primary">
                    Create Program
                </button>
                <a href="{{ route('admin.tv-programs.index') }}" class="btn-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
