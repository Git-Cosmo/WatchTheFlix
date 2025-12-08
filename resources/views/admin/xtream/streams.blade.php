@extends('layouts.admin')

@section('title', 'Manage Streams')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-xtream-hold-notice />
    
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">Stream Management</h1>
        <a href="{{ route('admin.xtream.index') }}" class="btn-secondary">Back to Dashboard</a>
    </div>

    <!-- Live TV Channels -->
    <div class="card p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Live TV Channels</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-700">
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Channel</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Number</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Country</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Status</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Stream ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($liveStreams as $channel)
                    <tr class="border-b border-dark-800">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                @if($channel->logo_url)
                                    <img src="{{ $channel->logo_url }}" alt="{{ $channel->name }}" class="w-8 h-8 object-contain">
                                @endif
                                <span class="font-medium">{{ $channel->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-dark-400">{{ $channel->channel_number }}</td>
                        <td class="py-3 px-4 text-sm">
                            <span class="px-2 py-1 bg-dark-700 rounded text-xs">{{ $channel->country }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded">Active</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-dark-400 font-mono">{{ $channel->id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $liveStreams->links() }}
        </div>
    </div>

    <!-- VOD Content -->
    <div class="card p-6">
        <h2 class="text-xl font-semibold mb-4">VOD Content</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-700">
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Title</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Type</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Rating</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Year</th>
                        <th class="text-left py-3 px-4 text-dark-400 font-medium">Stream ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vodStreams as $media)
                    <tr class="border-b border-dark-800">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                @if($media->poster_url)
                                    <img src="{{ $media->poster_url }}" alt="{{ $media->title }}" class="w-12 h-16 object-cover rounded">
                                @endif
                                <span class="font-medium">{{ $media->title }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <span class="px-2 py-1 bg-dark-700 rounded text-xs capitalize">{{ $media->type }}</span>
                        </td>
                        <td class="py-3 px-4 text-sm text-dark-400">
                            {{ $media->imdb_rating ? number_format($media->imdb_rating, 1) . '/10' : 'N/A' }}
                        </td>
                        <td class="py-3 px-4 text-sm text-dark-400">{{ $media->release_year }}</td>
                        <td class="py-3 px-4 text-sm text-dark-400 font-mono">{{ $media->id }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $vodStreams->links() }}
        </div>
    </div>
</div>
@endsection
