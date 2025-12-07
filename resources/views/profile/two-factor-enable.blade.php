@extends('layouts.app')

@section('title', 'Enable Two-Factor Authentication')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Enable Two-Factor Authentication</h1>

        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-4">Step 1: Scan QR Code</h2>
            <p class="text-dark-300 mb-4">
                Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.):
            </p>

            <div class="flex justify-center mb-6">
                <img src="{{ $qrCodeUrl }}" alt="QR Code" class="border-4 border-dark-700 rounded-lg">
            </div>

            <div class="bg-dark-800 p-4 rounded-lg mb-6">
                <p class="text-sm text-dark-300 mb-2">Or enter this code manually:</p>
                <code class="text-accent-400 font-mono text-lg">{{ $secret }}</code>
            </div>

            <h2 class="text-xl font-semibold mb-4">Step 2: Verify Code</h2>
            <p class="text-dark-300 mb-4">
                Enter the 6-digit code from your authenticator app to confirm:
            </p>

            <form method="POST" action="{{ route('two-factor.confirm') }}">
                @csrf

                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-dark-300 mb-2">
                        Verification Code
                    </label>
                    <input type="text" name="code" id="code" 
                           pattern="[0-9]{6}" maxlength="6"
                           class="input-field w-full text-center text-2xl tracking-widest" 
                           placeholder="000000" required autofocus>
                    @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary">
                        Confirm and Enable 2FA
                    </button>
                    <a href="{{ route('profile.settings') }}" class="btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
