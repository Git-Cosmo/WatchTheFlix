@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('forum.index') }}" class="text-accent-500 hover:text-accent-400 mb-2 inline-block">
                ← Back to Forum
            </a>
            <h1 class="text-3xl font-bold">{{ $category->name }}</h1>
            @if($category->description)
            <p class="text-dark-400 mt-2">{{ $category->description }}</p>
            @endif
        </div>
        <a href="{{ route('forum.create-thread', $category) }}" class="btn-primary">
            New Thread
        </a>
    </div>

    <div class="card">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dark-700">
                    <th class="text-left p-4">Thread</th>
                    <th class="text-center p-4 w-24">Replies</th>
                    <th class="text-center p-4 w-24">Views</th>
                    <th class="text-left p-4 w-48">Last Post</th>
                </tr>
            </thead>
            <tbody>
                @forelse($threads as $thread)
                <tr class="border-b border-dark-700 hover:bg-dark-800 transition">
                    <td class="p-4">
                        <div class="flex items-start space-x-3">
                            @if($thread->is_pinned)
                            <span class="text-accent-500">📌</span>
                            @endif
                            @if($thread->is_locked)
                            <span class="text-red-500">🔒</span>
                            @endif
                            <div class="flex-1">
                                <a href="{{ route('forum.thread', $thread) }}" class="text-lg font-semibold text-dark-100 hover:text-accent-500">
                                    {{ $thread->title }}
                                </a>
                                <div class="text-sm text-dark-400 mt-1">
                                    by {{ $thread->user->name }} • {{ $thread->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-center text-dark-300">{{ $thread->posts_count }}</td>
                    <td class="p-4 text-center text-dark-300">{{ $thread->views }}</td>
                    <td class="p-4">
                        @if($thread->latestPost)
                        <div class="text-sm">
                            <div class="text-dark-300">{{ $thread->latestPost->user->name }}</div>
                            <div class="text-dark-500">{{ $thread->latestPost->created_at->diffForHumans() }}</div>
                        </div>
                        @else
                        <div class="text-sm text-dark-500">No replies</div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-12 text-center text-dark-400">
                        No threads in this category yet.
                        <a href="{{ route('forum.create-thread', $category) }}" class="text-accent-500 hover:text-accent-400 block mt-2">
                            Be the first to start a discussion!
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $threads->links() }}
    </div>
</div>
@endsection
