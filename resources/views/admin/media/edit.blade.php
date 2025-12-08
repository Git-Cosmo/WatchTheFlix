@extends('layouts.admin')

@section('title', 'Edit Media')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Edit Media</h1>

        <form method="POST" action="{{ route('admin.media.update', $media) }}" class="card p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-dark-300 mb-2">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $media->title) }}" required
                       class="input-field w-full @error('title') border-red-500 @enderror">
                @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-dark-300 mb-2">Description</label>
                <textarea name="description" id="description" rows="4" class="input-field w-full">{{ old('description', $media->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-dark-300 mb-2">Type *</label>
                    <select name="type" id="type" required class="input-field w-full">
                        <option value="movie" {{ old('type', $media->type) == 'movie' ? 'selected' : '' }}>Movie</option>
                        <option value="series" {{ old('type', $media->type) == 'series' ? 'selected' : '' }}>Series</option>
                        <option value="episode" {{ old('type', $media->type) == 'episode' ? 'selected' : '' }}>Episode</option>
                    </select>
                </div>

                <div>
                    <label for="release_year" class="block text-sm font-medium text-dark-300 mb-2">Release Year</label>
                    <input type="number" name="release_year" id="release_year" value="{{ old('release_year', $media->release_year) }}" 
                           min="1900" max="{{ date('Y') + 5 }}" class="input-field w-full">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="poster_url" class="block text-sm font-medium text-dark-300 mb-2">Poster URL</label>
                    <input type="url" name="poster_url" id="poster_url" value="{{ old('poster_url', $media->poster_url) }}" 
                           class="input-field w-full" placeholder="https://...">
                </div>

                <div>
                    <label for="backdrop_url" class="block text-sm font-medium text-dark-300 mb-2">Backdrop URL</label>
                    <input type="url" name="backdrop_url" id="backdrop_url" value="{{ old('backdrop_url', $media->backdrop_url) }}" 
                           class="input-field w-full" placeholder="https://...">
                </div>
            </div>

            <div>
                <label for="stream_url" class="block text-sm font-medium text-dark-300 mb-2">Stream URL</label>
                <input type="url" name="stream_url" id="stream_url" value="{{ old('stream_url', $media->stream_url) }}" 
                       class="input-field w-full" placeholder="https://...">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="runtime" class="block text-sm font-medium text-dark-300 mb-2">Runtime (minutes)</label>
                    <input type="number" name="runtime" id="runtime" value="{{ old('runtime', $media->runtime) }}" 
                           min="1" class="input-field w-full">
                </div>

                <div>
                    <label for="imdb_rating" class="block text-sm font-medium text-dark-300 mb-2">IMDB Rating</label>
                    <input type="number" name="imdb_rating" id="imdb_rating" value="{{ old('imdb_rating', $media->imdb_rating) }}" 
                           min="0" max="10" step="0.1" class="input-field w-full">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-2">Available Platforms</label>
                <div class="bg-dark-800 rounded-lg p-4 max-h-64 overflow-y-auto">
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($platforms as $platform)
                            <label class="flex items-center">
                                <input type="checkbox" name="platforms[]" value="{{ $platform->id }}" 
                                       {{ in_array($platform->id, old('platforms', $media->platforms->pluck('id')->toArray())) ? 'checked' : '' }}
                                       class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                                <span class="ml-2 text-sm">{{ $platform->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <p class="text-xs text-dark-400 mt-1">Select the streaming platforms where this content is available</p>
            </div>

            <div class="flex items-center space-x-6">
                <label class="flex items-center">
                    <input type="checkbox" name="requires_real_debrid" value="1" 
                           {{ old('requires_real_debrid', $media->requires_real_debrid) ? 'checked' : '' }}
                           class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                    <span class="ml-2">Requires Real-Debrid</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" name="is_published" value="1" 
                           {{ old('is_published', $media->is_published) ? 'checked' : '' }}
                           class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                    <span class="ml-2">Published</span>
                </label>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="btn-primary">Update Media</button>
                <a href="{{ route('admin.media.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
