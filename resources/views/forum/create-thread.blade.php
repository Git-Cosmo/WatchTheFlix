@extends('layouts.app')

@section('title', 'Create New Thread')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('forum.category', $category) }}" class="text-accent-500 hover:text-accent-400 mb-2 inline-block">
                ← Back to {{ $category->name }}
            </a>
            <h1 class="text-3xl font-bold">Create New Thread</h1>
        </div>

        <form method="POST" action="{{ route('forum.store-thread', $category) }}" class="card p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-dark-300 mb-2">Thread Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="input-field w-full @error('title') border-red-500 @enderror"
                       placeholder="Enter a descriptive title for your thread">
                @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-dark-300 mb-2">Content *</label>
                <textarea name="body" id="body" rows="10" required
                          class="input-field w-full @error('body') border-red-500 @enderror"
                          placeholder="Write your message... (minimum 10 characters)">{{ old('body') }}</textarea>
                @error('body')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-dark-400 mt-1">Minimum 10 characters required</p>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="btn-primary">Create Thread</button>
                <a href="{{ route('forum.category', $category) }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
