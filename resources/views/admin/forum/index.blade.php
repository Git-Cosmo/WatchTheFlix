@extends('layouts.admin')

@section('title', 'Manage Forum Categories')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Manage Forum Categories</h1>
        <a href="{{ route('forum.admin.create') }}" class="btn-primary">Add New Category</a>
    </div>

    <div class="card">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dark-700">
                    <th class="text-left p-4">Order</th>
                    <th class="text-left p-4">Name</th>
                    <th class="text-left p-4">Description</th>
                    <th class="text-center p-4">Threads</th>
                    <th class="text-center p-4">Status</th>
                    <th class="text-right p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-b border-dark-700">
                    <td class="p-4 text-center font-mono">{{ $category->order }}</td>
                    <td class="p-4">
                        <div class="font-medium">{{ $category->name }}</div>
                        <div class="text-sm text-dark-400">{{ $category->slug }}</div>
                    </td>
                    <td class="p-4 text-dark-400">{{ Str::limit($category->description, 50) }}</td>
                    <td class="p-4 text-center">{{ $category->threads_count }}</td>
                    <td class="p-4 text-center">
                        <span class="px-2 py-1 rounded text-xs {{ $category->is_active ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="p-4 text-right space-x-2">
                        <a href="{{ route('forum.category', $category) }}" class="text-accent-500 hover:text-accent-400" target="_blank">View</a>
                        <a href="{{ route('forum.admin.edit', $category) }}" class="text-accent-500 hover:text-accent-400">Edit</a>
                        <form method="POST" action="{{ route('forum.admin.destroy', $category) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400" onclick="return confirm('Are you sure? This will delete all threads in this category.')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-dark-400">No forum categories found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
