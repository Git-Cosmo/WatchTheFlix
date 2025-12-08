@extends('layouts.app')

@section('title', 'Recovery Codes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Recovery Codes</h1>

        @if(session('recovery_codes'))
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4 mb-6">
            <h3 class="text-yellow-500 font-semibold mb-2">⚠️ New Recovery Codes Generated</h3>
            <p class="text-dark-300 text-sm">Your old recovery codes are no longer valid. Save these new codes in a safe place.</p>
        </div>
        @endif

        <div class="card p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Your Recovery Codes</h2>
            <p class="text-dark-300 mb-4">
                Store these recovery codes in a secure location. You can use them to access your account if you lose your 2FA device.
                Each code can only be used once.
            </p>

            <div class="bg-dark-900 p-6 rounded-lg mb-6">
                <div class="grid grid-cols-2 gap-4 font-mono text-lg">
                    @foreach(session('recovery_codes', $recoveryCodes ?? []) as $code)
                    <div class="flex items-center">
                        <span class="text-dark-500 mr-2">{{ $loop->iteration }}.</span>
                        <span class="text-accent-400">{{ $code }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-dark-800 border border-dark-700 rounded-lg p-4 mb-4">
                <h3 class="font-semibold mb-2 flex items-center gap-2">
                    <svg class="h-5 w-5 text-accent-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Important Notes:
                </h3>
                <ul class="list-disc list-inside text-dark-300 text-sm space-y-1">
                    <li>Each recovery code can only be used once</li>
                    <li>Store them in a password manager or secure location</li>
                    <li>Print them and keep them in a safe place</li>
                    <li>Don't share these codes with anyone</li>
                </ul>
            </div>

            <div class="flex gap-3">
                <form method="POST" action="{{ route('two-factor.recovery-codes.regenerate') }}" class="inline">
                    @csrf
                    <input type="password" name="password" placeholder="Enter password to regenerate" 
                           class="input-field" required>
                    <button type="submit" class="btn-secondary ml-2">
                        Regenerate Codes
                    </button>
                </form>
                <a href="{{ route('profile.settings') }}" class="btn-primary">
                    Back to Settings
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
