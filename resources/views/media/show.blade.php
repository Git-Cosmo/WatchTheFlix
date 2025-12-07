@extends('layouts.app')

@section('title', $media->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Media Header -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div>
            @if($media->poster_url)
            <img src="{{ $media->poster_url }}" alt="{{ $media->title }}" class="w-full rounded-lg shadow-lg">
            @else
            <div class="w-full h-96 bg-dark-800 rounded-lg flex items-center justify-center">
                <span class="text-dark-500 text-6xl">🎬</span>
            </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <h1 class="text-4xl font-bold mb-4">{{ $media->title }}</h1>
            
            <div class="flex flex-wrap gap-4 mb-6 text-sm text-dark-300">
                @if($media->release_year)
                <span>{{ $media->release_year }}</span>
                @endif
                @if($media->runtime)
                <span>• {{ $media->runtime }} min</span>
                @endif
                @if($media->rating)
                <span>• {{ $media->rating }}</span>
                @endif
                <span>• {{ ucfirst($media->type) }}</span>
            </div>

            @if($media->imdb_rating)
            <div class="mb-6">
                <span class="text-accent-500 text-xl">⭐ {{ $media->imdb_rating }}/10</span>
                <span class="text-dark-400 text-sm ml-2">({{ $media->ratings_count }} ratings)</span>
            </div>
            @endif

            @if($media->description)
            <p class="text-dark-300 mb-6">{{ $media->description }}</p>
            @endif

            <!-- Actions -->
            <div class="flex flex-wrap gap-4 mb-6">
                @auth
                <form method="POST" action="{{ route('watchlist.add', $media) }}">
                    @csrf
                    <button type="submit" class="btn-secondary">Add to Watchlist</button>
                </form>

                <form method="POST" action="{{ route('media.favorite', $media) }}">
                    @csrf
                    <button type="submit" class="btn-secondary">Favorite</button>
                </form>
                @endauth

                @if($media->stream_url && (!$media->requires_real_debrid || (auth()->check() && auth()->user()->hasRealDebridAccess())))
                <a href="{{ $media->stream_url }}" class="btn-primary" target="_blank">Play</a>
                @elseif($media->requires_real_debrid)
                <span class="btn-secondary opacity-50 cursor-not-allowed">Requires Real-Debrid</span>
                @endif
            </div>

            @if($media->genres)
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Genres</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($media->genres as $genre)
                    <span class="px-3 py-1 bg-dark-800 rounded-full text-sm">{{ $genre }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($media->platforms && $media->platforms->isNotEmpty())
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Available On</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($media->platforms as $platform)
                    <a href="{{ $platform->website_url }}" target="_blank" 
                       class="flex items-center gap-2 px-4 py-2 bg-dark-800 hover:bg-dark-700 rounded-lg transition-colors border border-dark-700 hover:border-accent-500">
                        @if($platform->logo_url)
                            <img src="{{ $platform->logo_url }}" alt="{{ $platform->name }}" class="w-5 h-5 object-contain">
                        @endif
                        <span class="text-sm font-medium">{{ $platform->name }}</span>
                        @if($platform->pivot->requires_subscription)
                            <span class="text-xs text-dark-400">(Subscription)</span>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Rating Section -->
    @auth
    <div class="card p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4">Rate this {{ $media->type }}</h2>
        <form method="POST" action="{{ route('media.rate', $media) }}" class="flex gap-2">
            @csrf
            @for($i = 1; $i <= 10; $i++)
            <button type="submit" name="rating" value="{{ $i }}" class="btn-secondary {{ $userRating && $userRating->rating == $i ? 'bg-accent-500' : '' }}">
                {{ $i }}
            </button>
            @endfor
        </form>
    </div>
    @endauth

    <!-- Reactions Section -->
    @auth
    <div class="card p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4">React to this {{ $media->type }}</h2>
        <div class="flex gap-4">
            @php
                $reactions = [
                    'like' => ['emoji' => '👍', 'label' => 'Like'],
                    'love' => ['emoji' => '❤️', 'label' => 'Love'],
                    'laugh' => ['emoji' => '😂', 'label' => 'Laugh'],
                    'sad' => ['emoji' => '😢', 'label' => 'Sad'],
                    'angry' => ['emoji' => '😠', 'label' => 'Angry'],
                ];
            @endphp
            @foreach($reactions as $type => $reaction)
            <form method="POST" action="{{ route('media.react', $media) }}" class="inline-block">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <button type="submit" class="flex flex-col items-center gap-1 px-4 py-3 rounded-lg border transition-colors {{ in_array($type, $userReactions) ? 'bg-accent-500 border-accent-500' : 'bg-dark-800 border-dark-700 hover:border-accent-500' }}">
                    <span class="text-2xl">{{ $reaction['emoji'] }}</span>
                    <span class="text-xs font-medium">{{ $reaction['label'] }}</span>
                </button>
            </form>
            @endforeach
        </div>
    </div>
    @endauth

    <!-- Comments Section -->
    <div class="card p-6">
        <h2 class="text-2xl font-bold mb-6">Comments ({{ $media->comments_count }})</h2>

        @auth
        <form method="POST" action="{{ route('media.comment', $media) }}" class="mb-8">
            @csrf
            <textarea name="content" rows="3" placeholder="Add a comment..." class="input-field w-full mb-4" required></textarea>
            <button type="submit" class="btn-primary">Post Comment</button>
        </form>
        @endauth

        <div class="space-y-4">
            @forelse($media->comments->where('parent_id', null) as $comment)
            <div class="border-b border-dark-700 pb-4">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full bg-accent-500 flex items-center justify-center">
                        <span class="text-sm font-medium">{{ substr($comment->user->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="font-semibold">{{ $comment->user->name }}</span>
                            <span class="text-sm text-dark-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-dark-300">{{ $comment->content }}</p>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-dark-400 py-8">No comments yet. Be the first to comment!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
