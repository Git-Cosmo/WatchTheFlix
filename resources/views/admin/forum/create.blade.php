@extends('layouts.admin')

@section('title', 'Create Forum Category')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Create Forum Category</h1>
        <p class="text-dark-400 mt-1">Add a new category to organize forum discussions</p>
    </div>

    <!-- Form Card -->
    <div class="card">
        <form method="POST" action="{{ route('admin.forum.admin.store') }}" class="p-8 space-y-6">
            @csrf

            <!-- Category Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-white mb-2">
                    Category Name <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="input-field w-full @error('name') border-red-500 @enderror"
                       placeholder="e.g., General Discussion, Support">
                @error('name')
                <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-white mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="input-field w-full"
                          placeholder="Brief description of what this category is for...">{{ old('description') }}</textarea>
                <p class="text-xs text-dark-400 mt-2">Optional - helps users understand the category's purpose</p>
            </div>

            <!-- Display Order -->
            <div>
                <label for="order" class="block text-sm font-semibold text-white mb-2">
                    Display Order <span class="text-red-400">*</span>
                </label>
                <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0" required
                       class="input-field w-full @error('order') border-red-500 @enderror"
                       placeholder="0">
                @error('order')
                <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
                @enderror
                <p class="text-xs text-dark-400 mt-2">Lower numbers appear first (0 is highest priority)</p>
            </div>

            <!-- Active Status -->
            <div class="bg-gh-bg-tertiary rounded-lg p-4 border border-gh-border">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="mt-1 rounded border-gh-border text-accent-500 focus:ring-accent-500 focus:ring-offset-gh-bg-tertiary">
                    <div>
                        <span class="font-medium text-white">Active Category</span>
                        <p class="text-sm text-dark-400 mt-0.5">When checked, this category will be visible to all users</p>
                    </div>
                </label>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center gap-4 pt-4 border-t border-gh-border">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Create Category
                </button>
                <a href="{{ route('admin.forum.admin.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
