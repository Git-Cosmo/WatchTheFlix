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
