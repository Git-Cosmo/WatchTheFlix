@extends('layouts.app')

@section('title', $thread->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('forum.category', $thread->category) }}" class="text-accent-500 hover:text-accent-400 mb-2 inline-block">
                ← Back to {{ $thread->category->name }}
            </a>
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold flex items-center gap-3">
                        @if($thread->is_pinned)
                        <span class="text-accent-500">📌</span>
                        @endif
                        @if($thread->is_locked)
                        <span class="text-red-500">🔒</span>
                        @endif
                        {{ $thread->title }}
                    </h1>
                    <div class="text-dark-400 mt-2">
                        {{ $thread->views }} views • {{ $thread->posts->count() }} replies
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <form method="POST" action="{{ route('forum.subscribe', $thread) }}">
                        @csrf
                        <button type="submit" class="btn-secondary text-sm">
                            {{ $isSubscribed ? 'Unsubscribe' : 'Subscribe' }}
                        </button>
                    </form>
                    
                    @can('admin-access')
                    <form method="POST" action="{{ route('forum.pin', $thread) }}">
                        @csrf
                        <button type="submit" class="btn-secondary text-sm">
                            {{ $thread->is_pinned ? 'Unpin' : 'Pin' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('forum.lock', $thread) }}">
                        @csrf
                        <button type="submit" class="btn-secondary text-sm">
                            {{ $thread->is_locked ? 'Unlock' : 'Lock' }}
                        </button>
                    </form>
                    @endcan
                    
                    @if(auth()->id() === $thread->user_id || auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('forum.destroy', $thread) }}" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger text-sm">Delete</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Original Post -->
        <div class="card p-6 mb-6">
            <div class="flex items-start space-x-4">
                <div class="w-16 h-16 rounded-full bg-accent-500 flex items-center justify-center text-xl font-bold flex-shrink-0">
                    {{ substr($thread->user->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="font-semibold text-lg">{{ $thread->user->name }}</div>
                            <div class="text-sm text-dark-400">{{ $thread->created_at->format('M d, Y \a\t h:i A') }}</div>
                        </div>
                    </div>
                    <div class="text-dark-200 whitespace-pre-wrap">{{ $thread->body }}</div>
                </div>
            </div>
        </div>

        <!-- Replies -->
        @foreach($thread->posts as $post)
        <div class="card p-6 mb-4">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 rounded-full bg-accent-500 flex items-center justify-center font-bold flex-shrink-0">
                    {{ substr($post->user->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="font-semibold">{{ $post->user->name }}</div>
                            <div class="text-sm text-dark-400">{{ $post->created_at->format('M d, Y \a\t h:i A') }}</div>
                        </div>
                    </div>
                    <div class="text-dark-200 whitespace-pre-wrap">{{ $post->body }}</div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Reply Form -->
        @if(!$thread->is_locked || auth()->user()->isAdmin())
        <div class="card p-6">
            <h3 class="text-xl font-semibold mb-4">Post a Reply</h3>
            <form method="POST" action="{{ route('forum.reply', $thread) }}">
                @csrf
                <div class="mb-4">
                    <textarea name="body" rows="6" required
                              class="input-field w-full @error('body') border-red-500 @enderror"
                              placeholder="Write your reply... (minimum 10 characters)">{{ old('body') }}</textarea>
                    @error('body')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn-primary">Post Reply</button>
            </form>
        </div>
        @else
        <div class="card p-6 text-center">
            <p class="text-red-500">🔒 This thread is locked. No new replies can be posted.</p>
        </div>
        @endif
    </div>
</div>
@endsection
