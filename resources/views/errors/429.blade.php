@extends('layouts.app')

@section('title', '429 - Too Many Requests')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center px-4">
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-accent-500 mb-4">429</h1>
            <div class="w-20 h-1 bg-accent-500 mx-auto mb-8"></div>
        </div>
        
        <h2 class="text-3xl font-bold mb-4">Too Many Requests</h2>
        <p class="text-lg text-gh-text-muted mb-8 max-w-md mx-auto">
            Whoa there! You're making too many requests. Please slow down and try again in a moment.
        </p>
        
        <div class="flex items-center justify-center gap-2 mb-8 text-accent-400">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium">Please wait a moment before trying again</span>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" class="btn-primary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Go Home
            </a>
            <a href="{{ url()->previous() }}" class="btn-secondary">
                <svg class="h-5 w-5 mr-2 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
            </a>
        </div>
    </div>
</div>
@endsection
