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

            <!-- Social Sharing -->
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-dark-400 mb-3">Share this:</h3>
                <div class="flex flex-wrap gap-3">
                    @php
                        $shareUrl = route('media.show', $media);
                        $shareText = urlencode($media->title . ' - Watch on WatchTheFlix');
                    @endphp
                    
                    <!-- Twitter/X -->
                    <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ urlencode($shareUrl) }}" 
                       target="_blank" 
                       class="flex items-center gap-2 px-4 py-2 bg-[#1DA1F2] hover:bg-[#1a8cd8] rounded-lg transition-colors text-white text-sm font-medium">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                        Twitter
                    </a>

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" 
                       target="_blank" 
                       class="flex items-center gap-2 px-4 py-2 bg-[#1877F2] hover:bg-[#166fe5] rounded-lg transition-colors text-white text-sm font-medium">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Facebook
                    </a>

                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" 
                       target="_blank" 
                       class="flex items-center gap-2 px-4 py-2 bg-[#0A66C2] hover:bg-[#095196] rounded-lg transition-colors text-white text-sm font-medium">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        LinkedIn
                    </a>

                    <!-- WhatsApp -->
                    <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ urlencode($shareUrl) }}" 
                       target="_blank" 
                       class="flex items-center gap-2 px-4 py-2 bg-[#25D366] hover:bg-[#20bd5a] rounded-lg transition-colors text-white text-sm font-medium">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        WhatsApp
                    </a>

                    <!-- Copy Link -->
                    <button onclick="copyToClipboard('{{ $shareUrl }}')" 
                            class="flex items-center gap-2 px-4 py-2 bg-dark-800 hover:bg-dark-700 rounded-lg transition-colors border border-dark-700 hover:border-accent-500 text-sm font-medium">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                        Copy Link
                    </button>
                </div>
            </div>

            <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    // Show a temporary success message
                    const btn = event.target.closest('button');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<svg class="h-4 w-4 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Copied!';
                    btn.classList.add('bg-green-600', 'border-green-500');
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('bg-green-600', 'border-green-500');
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy:', err);
                });
            }
            </script>

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
