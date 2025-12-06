@extends('layouts.app')

@section('title', 'Edit Forum Category')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Edit Forum Category</h1>

        <form method="POST" action="{{ route('forum.admin.update', $category) }}" class="card p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-dark-300 mb-2">Category Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                       class="input-field w-full @error('name') border-red-500 @enderror">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-dark-300 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="input-field w-full">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label for="order" class="block text-sm font-medium text-dark-300 mb-2">Display Order *</label>
                <input type="number" name="order" id="order" value="{{ old('order', $category->order) }}" min="0" required
                       class="input-field w-full @error('order') border-red-500 @enderror">
                @error('order')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-dark-400 mt-1">Lower numbers appear first</p>
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                    <span class="ml-2">Active</span>
                </label>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="btn-primary">Update Category</button>
                <a href="{{ route('forum.admin.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
