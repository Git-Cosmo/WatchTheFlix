@extends('layouts.admin')

@section('title', 'Manage Subtitles - ' . $media->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.media.edit', $media) }}" class="text-accent-500 hover:text-accent-400">
            ← Back to Media
        </a>
    </div>

    <h1 class="text-3xl font-bold mb-6">{{ __('Manage Subtitles') }}: {{ $media->title }}</h1>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-6">
        {{ session('error') }}
    </div>
    @endif

    <!-- Current Subtitles -->
    <div class="bg-dark-800 rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">{{ __('Current Subtitles') }}</h2>

        @if($media->subtitles && count($media->subtitles) > 0)
        <div class="space-y-3">
            @foreach($media->subtitles as $lang => $subtitle)
            <div class="flex items-center justify-between bg-dark-700 p-4 rounded">
                <div>
                    <span class="font-medium">{{ $subtitle['language_name'] }}</span>
                    <span class="text-dark-400 text-sm ml-2">({{ $subtitle['language'] }})</span>
                </div>
                <div class="flex gap-3">
                    <a href="{{ $subtitle['url'] }}" target="_blank" class="text-accent-500 hover:text-accent-400">
                        {{ __('Download') }}
                    </a>
                    <form method="POST" action="{{ route('admin.media.subtitles.destroy', [$media, $lang]) }}" 
                          onsubmit="return confirm('{{ __('Are you sure you want to delete this subtitle?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-400">
                            {{ __('Delete') }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-dark-400">{{ __('No subtitles uploaded yet.') }}</p>
        @endif
    </div>

    <!-- Add New Subtitle -->
    <div class="bg-dark-800 rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">{{ __('Add New Subtitle') }}</h2>

        <form method="POST" action="{{ route('admin.media.subtitles.store', $media) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">{{ __('Subtitle File') }} (.srt or .vtt)</label>
                <input type="file" name="subtitle_file" accept=".srt,.vtt" required
                       class="w-full px-4 py-2 bg-dark-700 border border-dark-600 rounded-lg focus:outline-none focus:border-accent-500">
                @error('subtitle_file')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">{{ __('Language Code') }}</label>
                <select name="language" required
                        class="w-full px-4 py-2 bg-dark-700 border border-dark-600 rounded-lg focus:outline-none focus:border-accent-500">
                    <option value="en">en - English</option>
                    <option value="es">es - Spanish</option>
                    <option value="fr">fr - French</option>
                    <option value="de">de - German</option>
                    <option value="it">it - Italian</option>
                    <option value="pt">pt - Portuguese</option>
                    <option value="ru">ru - Russian</option>
                    <option value="zh">zh - Chinese</option>
                    <option value="ja">ja - Japanese</option>
                    <option value="ko">ko - Korean</option>
                    <option value="ar">ar - Arabic</option>
                </select>
                @error('language')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">{{ __('Language Name') }}</label>
                <input type="text" name="language_name" required placeholder="e.g., English, Español"
                       class="w-full px-4 py-2 bg-dark-700 border border-dark-600 rounded-lg focus:outline-none focus:border-accent-500">
                @error('language_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">
                    {{ __('Upload Subtitle') }}
                </button>
                <a href="{{ route('admin.media.edit', $media) }}" class="btn-secondary">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
