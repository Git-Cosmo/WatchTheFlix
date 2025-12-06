@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto">
        <div class="card p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Login to WatchTheFlix</h2>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-dark-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="input-field w-full @error('email') border-red-500 @enderror">
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
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                        <span class="ml-2 text-sm text-dark-300">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full">
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-dark-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-accent-500 hover:text-accent-400">Sign up</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
