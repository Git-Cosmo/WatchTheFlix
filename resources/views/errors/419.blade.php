@extends('layouts.app')

@section('title', '419 - Session Expired')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center px-4">
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-accent-500 mb-4">419</h1>
            <div class="w-20 h-1 bg-accent-500 mx-auto mb-8"></div>
        </div>
        
        <h2 class="text-3xl font-bold mb-4">Session Expired</h2>
        <p class="text-lg text-gh-text-muted mb-8 max-w-md mx-auto">
            Your session has expired. Please refresh the page and try again.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.location.reload()" class="btn-primary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh Page
            </button>
            <a href="{{ route('home') }}" class="btn-secondary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Go Home
            </a>
        </div>
    </div>
</div>
@endsection
