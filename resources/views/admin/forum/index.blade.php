@extends('layouts.admin')

@section('title', 'Manage Forum Categories')

@section('content')
<div class="max-w-7xl">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Manage Forum Categories</h1>
                <p class="text-dark-400 mt-1">Organize and manage forum categories and discussions</p>
            </div>
            <a href="{{ route('admin.forum.admin.create') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Category
            </a>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gh-bg-tertiary">
                    <tr class="border-b border-gh-border">
                        <th class="text-center px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Order</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Category</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Description</th>
                        <th class="text-center px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Threads</th>
                        <th class="text-center px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Status</th>
                        <th class="text-right px-6 py-4 text-sm font-semibold text-dark-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gh-border">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gh-bg-tertiary/50 transition-colors duration-150">
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-accent-500/10 text-accent-400 font-mono font-bold text-sm">
                                {{ $category->order }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-white">{{ $category->name }}</div>
                            <div class="text-sm text-dark-400 mt-0.5 font-mono">{{ $category->slug }}</div>
                        </td>
                        <td class="px-6 py-4 text-dark-300">{{ Str::limit($category->description, 60) }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400">
                                {{ $category->threads_count }} threads
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $category->is_active ? 'bg-green-400' : 'bg-red-400' }} mr-1.5"></span>
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('forum.category', $category) }}" target="_blank" class="text-dark-400 hover:text-accent-400 transition-colors" title="View">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.forum.admin.edit', $category) }}" class="text-accent-500 hover:text-accent-400 transition-colors font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.forum.admin.destroy', $category) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400 transition-colors font-medium" onclick="return confirm('Are you sure? This will delete all threads in this category.')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="h-12 w-12 text-dark-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                                <p class="text-dark-400 text-lg">No forum categories found</p>
                                <a href="{{ route('admin.forum.admin.create') }}" class="btn-primary text-sm">Create your first category</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
