@extends('layouts.admin')

@section('title', 'Manage Media')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Manage Media</h1>
        <a href="{{ route('admin.media.create') }}" class="btn-primary">Add New Media</a>
    </div>

    <div class="card">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dark-700">
                    <th class="text-left p-4">Title</th>
                    <th class="text-left p-4">Type</th>
                    <th class="text-left p-4">Year</th>
                    <th class="text-left p-4">Status</th>
                    <th class="text-right p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($media as $item)
                <tr class="border-b border-dark-700">
                    <td class="p-4">
                        <div class="font-medium">{{ $item->title }}</div>
                        <div class="text-sm text-dark-400">{{ Str::limit($item->description, 50) }}</div>
                    </td>
                    <td class="p-4">{{ ucfirst($item->type) }}</td>
                    <td class="p-4">{{ $item->release_year }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded text-xs {{ $item->is_published ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                            {{ $item->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="p-4 text-right space-x-2">
                        <a href="{{ route('admin.media.edit', $item) }}" class="text-accent-500 hover:text-accent-400">Edit</a>
                        <form method="POST" action="{{ route('admin.media.destroy', $item) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-dark-400">No media found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $media->links() }}
    </div>
</div>
@endsection
