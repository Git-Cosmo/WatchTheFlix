@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto">
        <div class="card p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Join WatchTheFlix</h2>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                @if(isset($invite) && $invite)
                <input type="hidden" name="invite_code" value="{{ $invite->code }}">
                <div class="mb-4 p-4 bg-accent-900 border border-accent-700 rounded-lg">
                    <p class="text-sm text-accent-300">
                        You've been invited! Email: {{ $invite->email }}
                    </p>
                </div>
                @endif

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-dark-300 mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                           class="input-field w-full @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-dark-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $invite->email ?? '') }}" required
                           class="input-field w-full @error('email') border-red-500 @enderror" {{ isset($invite) ? 'readonly' : '' }}>
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-dark-300 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                           class="input-field w-full @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-dark-300 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="input-field w-full">
                </div>

                <button type="submit" class="btn-primary w-full">
                    Create Account
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-dark-400">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-accent-500 hover:text-accent-400">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
