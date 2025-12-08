@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Settings</h1>

        <!-- Real-Debrid Settings -->
        <div class="card p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Real-Debrid Integration</h2>
            <form method="POST" action="{{ route('profile.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="real_debrid_enabled" value="1" 
                               {{ $user->real_debrid_enabled ? 'checked' : '' }}
                               class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                        <span class="ml-2">Enable Real-Debrid</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label for="real_debrid_token" class="block text-sm font-medium text-dark-300 mb-2">
                        Real-Debrid API Token
                    </label>
                    <input type="text" name="real_debrid_token" id="real_debrid_token" 
                           value="{{ old('real_debrid_token', $user->real_debrid_token) }}"
                           class="input-field w-full" placeholder="Your Real-Debrid API token">
                    @error('real_debrid_token')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-dark-400 mt-1">
                        Get your API token from <a href="https://real-debrid.com/apitoken" target="_blank" class="text-accent-500">Real-Debrid</a>
                    </p>
                </div>

                <button type="submit" class="btn-primary">Save Real-Debrid Settings</button>
            </form>
        </div>

        <!-- Parental Controls -->
        <div class="card p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Parental Controls</h2>
            <form method="POST" action="{{ route('profile.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="parental_control_enabled" value="1" 
                               {{ $user->parental_control_enabled ? 'checked' : '' }}
                               class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                        <span class="ml-2">Enable Parental Controls</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label for="parental_control_pin" class="block text-sm font-medium text-dark-300 mb-2">
                        Parental Control PIN (4 digits)
                    </label>
                    <input type="text" name="parental_control_pin" id="parental_control_pin" 
                           maxlength="4" pattern="[0-9]{4}"
                           class="input-field w-full" placeholder="1234">
                </div>

                <button type="submit" class="btn-primary">Save Parental Controls</button>
            </form>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="card p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Two-Factor Authentication (2FA)</h2>
            <p class="text-dark-300 mb-4">Add an extra layer of security to your account with two-factor authentication.</p>

            @if(session('recovery_codes'))
            <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4 mb-4">
                <h3 class="text-yellow-500 font-semibold mb-2">⚠️ Save Your Recovery Codes</h3>
                <p class="text-dark-300 mb-3 text-sm">Store these recovery codes in a safe place. You can use them to access your account if you lose your 2FA device.</p>
                <div class="bg-dark-900 p-4 rounded font-mono text-sm grid grid-cols-2 gap-2">
                    @foreach(session('recovery_codes') as $code)
                    <div>{{ $code }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($user->two_factor_enabled)
            <div class="flex items-center gap-3 mb-4">
                <span class="flex items-center gap-2 text-green-500">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">Enabled</span>
                </span>
                <span class="text-dark-400 text-sm">Two-factor authentication is active on your account</span>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('two-factor.recovery-codes') }}" class="btn-secondary">
                    View Recovery Codes
                </a>
                <form method="POST" action="{{ route('two-factor.disable') }}" class="inline">
                    @csrf
                    <input type="password" name="password" placeholder="Enter password to disable" 
                           class="input-field" required>
                    <button type="submit" class="btn-secondary ml-2">
                        Disable 2FA
                    </button>
                </form>
            </div>
            @else
            <div class="flex items-center gap-3 mb-4">
                <span class="flex items-center gap-2 text-dark-400">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">Disabled</span>
                </span>
                <span class="text-dark-400 text-sm">Two-factor authentication is not enabled</span>
            </div>

            <form method="POST" action="{{ route('two-factor.enable') }}">
                @csrf
                <button type="submit" class="btn-primary">
                    Enable 2FA
                </button>
            </form>
            @endif
        </div>

        <!-- Change Password -->
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-4">Change Password</h2>
            <form method="POST" action="{{ route('profile.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-dark-300 mb-2">
                        New Password
                    </label>
                    <input type="password" name="password" id="password" class="input-field w-full">
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-dark-300 mb-2">
                        Confirm Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="input-field w-full">
                </div>

                <button type="submit" class="btn-primary">Change Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
