@props(['media', 'streamUrl' => null, 'subtitles' => [], 'autoplay' => false])

<div class="video-player-container mb-8">
    @if($streamUrl || $media->stream_url)
    <!-- Video.js Player -->
    <link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />
    <link href="https://unpkg.com/@videojs/themes@1/dist/fantasy/index.css" rel="stylesheet" />
    
    <video 
        id="watch-flix-player" 
        class="video-js vjs-theme-fantasy vjs-16-9" 
        controls 
        preload="auto"
        @if($autoplay) autoplay @endif
        data-setup='{"fluid": true, "aspectRatio": "16:9"}'
    >
        <source src="{{ $streamUrl ?? $media->stream_url }}" type="application/x-mpegURL">
        
        @if(is_array($subtitles) && count($subtitles) > 0)
            @foreach($subtitles as $lang => $subtitleUrl)
            <track 
                kind="subtitles" 
                src="{{ $subtitleUrl }}" 
                srclang="{{ $lang }}" 
                label="{{ strtoupper($lang) }}"
                @if($loop->first) default @endif
            >
            @endforeach
        @elseif(is_object($media) && isset($media->subtitles))
            @php
                $mediaSubtitles = is_string($media->subtitles) ? json_decode($media->subtitles, true) : $media->subtitles;
            @endphp
            @if(is_array($mediaSubtitles))
                @foreach($mediaSubtitles as $lang => $subtitleUrl)
                <track 
                    kind="subtitles" 
                    src="{{ $subtitleUrl }}" 
                    srclang="{{ $lang }}" 
                    label="{{ strtoupper($lang) }}"
                    @if($loop->first) default @endif
                >
                @endforeach
            @endif
        @endif
        
        <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a web browser that
            <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
        </p>
    </video>

    <script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
    <script src="https://unpkg.com/@videojs/http-streaming@3/dist/videojs-http-streaming.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var player = videojs('watch-flix-player', {
                controls: true,
                autoplay: {{ $autoplay ? 'true' : 'false' }},
                preload: 'auto',
                fluid: true,
                responsive: true,
                playbackRates: [0.5, 0.75, 1, 1.25, 1.5, 2],
                controlBar: {
                    children: [
                        'playToggle',
                        'volumePanel',
                        'currentTimeDisplay',
                        'timeDivider',
                        'durationDisplay',
                        'progressControl',
                        'remainingTimeDisplay',
                        'playbackRateMenuButton',
                        'chaptersButton',
                        'subtitlesButton',
                        'captionsButton',
                        'qualitySelector',
                        'pictureInPictureToggle',
                        'fullscreenToggle'
                    ]
                },
                html5: {
                    vhs: {
                        overrideNative: true
                    },
                    nativeVideoTracks: false,
                    nativeAudioTracks: false,
                    nativeTextTracks: false
                }
            });

            // Error handling
            player.on('error', function() {
                var error = player.error();
                console.error('Video.js Error:', error);
                
                if (error) {
                    var errorDisplay = player.getChild('errorDisplay');
                    if (errorDisplay) {
                        errorDisplay.fillWith('Unable to play video. Please check the stream URL or try again later.');
                    }
                }
            });

            // Save playback position
            @auth
            player.on('timeupdate', function() {
                var currentTime = player.currentTime();
                if (currentTime > 0 && currentTime % 30 === 0) { // Save every 30 seconds
                    localStorage.setItem('video_position_{{ $media->id }}', currentTime);
                }
            });

            // Restore playback position
            var savedPosition = localStorage.getItem('video_position_{{ $media->id }}');
            if (savedPosition && savedPosition > 0) {
                player.on('loadedmetadata', function() {
                    player.currentTime(parseFloat(savedPosition));
                });
            }

            // Clear position when video ends
            player.on('ended', function() {
                localStorage.removeItem('video_position_{{ $media->id }}');
            });
            @endauth

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.target.tagName.toLowerCase() === 'input' || e.target.tagName.toLowerCase() === 'textarea') {
                    return;
                }

                switch(e.key) {
                    case ' ':
                    case 'k':
                        e.preventDefault();
                        if (player.paused()) {
                            player.play();
                        } else {
                            player.pause();
                        }
                        break;
                    case 'f':
                        e.preventDefault();
                        if (player.isFullscreen()) {
                            player.exitFullscreen();
                        } else {
                            player.requestFullscreen();
                        }
                        break;
                    case 'm':
                        e.preventDefault();
                        player.muted(!player.muted());
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        player.currentTime(player.currentTime() - 10);
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        player.currentTime(player.currentTime() + 10);
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        player.volume(Math.min(player.volume() + 0.1, 1));
                        break;
                    case 'ArrowDown':
                        e.preventDefault();
                        player.volume(Math.max(player.volume() - 0.1, 0));
                        break;
                }
            });

            console.log('Video.js player initialized for: {{ $media->title }}');
        });
    </script>

    <!-- Player Controls Info -->
    <div class="mt-4 text-sm text-dark-400">
        <p class="font-semibold mb-2">{{ __('Keyboard Shortcuts') }}:</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            <div><kbd class="px-2 py-1 bg-dark-800 rounded">Space/K</kbd> {{ __('Play/Pause') }}</div>
            <div><kbd class="px-2 py-1 bg-dark-800 rounded">F</kbd> {{ __('Fullscreen') }}</div>
            <div><kbd class="px-2 py-1 bg-dark-800 rounded">M</kbd> {{ __('Mute') }}</div>
            <div><kbd class="px-2 py-1 bg-dark-800 rounded">←/→</kbd> {{ __('Seek ±10s') }}</div>
            <div><kbd class="px-2 py-1 bg-dark-800 rounded">↑/↓</kbd> {{ __('Volume') }}</div>
        </div>
    </div>
    @else
    <!-- No Stream Available -->
    <div class="bg-dark-800 rounded-lg p-12 text-center">
        <div class="text-6xl mb-4">🎬</div>
        <h3 class="text-xl font-semibold mb-2">{{ __('Stream Not Available') }}</h3>
        <p class="text-dark-400">{{ __('This content does not have a playable stream URL configured.') }}</p>
        @can('update', $media)
        <a href="{{ route('admin.media.edit', $media) }}" class="mt-4 inline-block px-6 py-2 bg-accent-600 hover:bg-accent-700 rounded-lg transition">
            {{ __('Add Stream URL') }}
        </a>
        @endcan
    </div>
    @endif
</div>

<style>
    .video-js {
        width: 100%;
        height: auto;
    }
    
    .video-js .vjs-big-play-button {
        font-size: 3em;
        line-height: 1.5em;
        height: 1.5em;
        width: 3em;
        border-radius: 0.3em;
        background-color: rgba(43, 51, 63, 0.7);
        border: 0.06666em solid #fff;
        margin-top: -0.75em;
        margin-left: -1.5em;
    }
    
    kbd {
        font-family: monospace;
        font-size: 0.9em;
    }
</style>
