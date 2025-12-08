@extends('layouts.app')

@section('title', 'Xtream API Configuration')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-xtream-hold-notice />
    
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">Xtream API Configuration</h1>
            <a href="{{ route('admin.xtream.index') }}" class="btn-secondary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>

        <!-- API Endpoints -->
        <div class="card p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">API Endpoints</h2>
            <p class="text-dark-300 mb-6">These endpoints are available for IPTV applications and players.</p>

            <div class="space-y-4">
                @foreach($endpoints as $name => $url)
                <div class="bg-dark-800 p-4 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-accent-400">{{ $name }}</h3>
                        <button onclick="copyToClipboard('{{ $url }}', event)" class="text-sm text-dark-400 hover:text-accent-400">
                            <svg class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Copy
                        </button>
                    </div>
                    <code class="text-sm text-dark-400 break-all">{{ $url }}</code>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Player Configuration Examples -->
        <div class="card p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">IPTV Player Configuration</h2>
            
            <div class="space-y-6">
                <!-- TiviMate -->
                <div class="border-l-4 border-accent-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">TiviMate</h3>
                    <ol class="list-decimal list-inside space-y-1 text-sm text-dark-300">
                        <li>Add playlist using "Xtream Codes API"</li>
                        <li>Server URL: <code class="bg-dark-800 px-2 py-1 rounded">{{ $baseUrl }}/api/xtream</code></li>
                        <li>Username: Your email address</li>
                        <li>Password: Your account password</li>
                    </ol>
                </div>

                <!-- Perfect Player -->
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">Perfect Player</h3>
                    <ol class="list-decimal list-inside space-y-1 text-sm text-dark-300">
                        <li>Settings → General → Playlist</li>
                        <li>Add playlist → Xtream Codes</li>
                        <li>Server: <code class="bg-dark-800 px-2 py-1 rounded">{{ $baseUrl }}/api/xtream</code></li>
                        <li>Username: Your email</li>
                        <li>Password: Your password</li>
                    </ol>
                </div>

                <!-- GSE Smart IPTV -->
                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">GSE Smart IPTV</h3>
                    <ol class="list-decimal list-inside space-y-1 text-sm text-dark-300">
                        <li>Add "Xtream Codes" playlist</li>
                        <li>Server URL: <code class="bg-dark-800 px-2 py-1 rounded">{{ $baseUrl }}/api/xtream/player_api.php</code></li>
                        <li>Username: Your email</li>
                        <li>Password: Your password</li>
                    </ol>
                </div>

                <!-- M3U URL Method -->
                <div class="border-l-4 border-yellow-500 pl-4">
                    <h3 class="font-semibold text-lg mb-2">M3U URL (Any Player)</h3>
                    <p class="text-sm text-dark-300 mb-2">For players that only support M3U URLs:</p>
                    <code class="text-sm bg-dark-800 px-3 py-2 rounded block break-all">
                        {{ $baseUrl }}/api/xtream/playlist.m3u?username=YOUR_EMAIL&password=YOUR_PASSWORD
                    </code>
                </div>
            </div>
        </div>

        <!-- API Test -->
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-4">Test API Connection</h2>
            <p class="text-dark-300 mb-4">Test your Xtream API credentials.</p>

            <form method="POST" action="{{ route('admin.xtream.test-endpoint') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Endpoint</label>
                    <select name="endpoint" class="input-field w-full">
                        <option value="player_api">Player API</option>
                        <option value="get_live_streams">Get Live Streams</option>
                        <option value="get_vod_streams">Get VOD Streams</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Username (Email)</label>
                    <input type="email" name="username" class="input-field w-full" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Password</label>
                    <input type="password" name="password" class="input-field w-full" required>
                </div>

                <button type="submit" class="btn-primary">
                    <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Test Connection
                </button>
            </form>

            @if(session('test_result'))
            <div class="mt-4 bg-green-500/10 border border-green-500/30 rounded-lg p-4">
                <h3 class="text-green-500 font-semibold mb-2">✓ Test Successful</h3>
                <pre class="text-xs bg-dark-900 p-3 rounded overflow-auto">{{ json_encode(session('test_result'), JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, event) {
    navigator.clipboard.writeText(text).then(() => {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Copied!';
        btn.classList.add('text-green-400');
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('text-green-400');
        }, 2000);
    });
}
</script>
@endsection
