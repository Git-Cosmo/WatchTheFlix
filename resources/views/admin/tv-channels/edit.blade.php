@extends('layouts.admin')

@section('title', 'Edit TV Channel')

@section('content')
<div class="max-w-3xl">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Edit TV Channel</h1>
        <p class="text-dark-400 mt-2">Update channel information</p>
    </div>

    <form method="POST" action="{{ route('admin.tv-channels.update', $channel) }}" class="card p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Channel Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-dark-300 mb-2">Channel Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $channel->name) }}" required class="input-field w-full">
                @error('name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Channel Number -->
                <div>
                    <label for="channel_number" class="block text-sm font-medium text-dark-300 mb-2">Channel Number *</label>
                    <input type="number" name="channel_number" id="channel_number" value="{{ old('channel_number', $channel->channel_number) }}" required class="input-field w-full">
                    @error('channel_number')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-medium text-dark-300 mb-2">Country *</label>
                    <select name="country" id="country" required class="input-field w-full">
                        <option value="UK" {{ old('country', $channel->country) === 'UK' ? 'selected' : '' }}>United Kingdom</option>
                        <option value="US" {{ old('country', $channel->country) === 'US' ? 'selected' : '' }}>United States</option>
                    </select>
                    @error('country')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Channel ID -->
            <div>
                <label for="channel_id" class="block text-sm font-medium text-dark-300 mb-2">Channel ID (for EPG matching)</label>
                <input type="text" name="channel_id" id="channel_id" value="{{ old('channel_id', $channel->channel_id) }}" class="input-field w-full">
                <p class="mt-1 text-xs text-dark-500">Used to match with EPG data provider</p>
                @error('channel_id')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-dark-300 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" class="input-field w-full">{{ old('description', $channel->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Logo URL -->
            <div>
                <label for="logo_url" class="block text-sm font-medium text-dark-300 mb-2">Logo URL</label>
                <input type="url" name="logo_url" id="logo_url" value="{{ old('logo_url', $channel->logo_url) }}" class="input-field w-full">
                @error('logo_url')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stream URL -->
            <div>
                <label for="stream_url" class="block text-sm font-medium text-dark-300 mb-2">Stream URL</label>
                <input type="url" name="stream_url" id="stream_url" value="{{ old('stream_url', $channel->stream_url) }}" class="input-field w-full">
                @error('stream_url')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Is Active -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $channel->is_active) ? 'checked' : '' }} class="h-4 w-4 text-accent-500 focus:ring-accent-500 border-dark-600 rounded bg-dark-700">
                <label for="is_active" class="ml-3 text-sm text-dark-300">
                    Channel is active
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gh-border">
            <a href="{{ route('admin.tv-channels.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Channel</button>
        </div>
    </form>
</div>
@endsection
