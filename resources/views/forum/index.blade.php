@extends('layouts.app')

@section('title', 'Community Forum')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Community Forum</h1>
    </div>

    <div class="space-y-4">
        @forelse($categories as $category)
        <div class="card p-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <a href="{{ route('forum.category', $category) }}" class="text-xl font-semibold text-accent-500 hover:text-accent-400">
                        {{ $category->name }}
                    </a>
                    @if($category->description)
                    <p class="text-dark-400 mt-2">{{ $category->description }}</p>
                    @endif
                    
                    @if($category->latestThread)
                    <div class="mt-4 text-sm text-dark-400">
                        <span>Latest: </span>
                        <a href="{{ route('forum.thread', $category->latestThread) }}" class="text-accent-500 hover:text-accent-400">
                            {{ $category->latestThread->title }}
                        </a>
                        <span> by {{ $category->latestThread->user->name }}</span>
                        <span> • {{ $category->latestThread->created_at->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="text-center ml-6">
                    <div class="text-2xl font-bold text-accent-500">{{ $category->threads_count }}</div>
                    <div class="text-sm text-dark-400">Threads</div>
                </div>
            </div>
        </div>
        @empty
        <div class="card p-12 text-center">
            <p class="text-dark-400">No forum categories available yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
