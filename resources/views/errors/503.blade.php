@extends('layouts.app')

@section('title', '503 - Maintenance Mode')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center px-4">
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-accent-500 mb-4">503</h1>
            <div class="w-20 h-1 bg-accent-500 mx-auto mb-8"></div>
        </div>
        
        <h2 class="text-3xl font-bold mb-4">Maintenance Mode</h2>
        <p class="text-lg text-gh-text-muted mb-8 max-w-md mx-auto">
            We're performing scheduled maintenance. We'll be back soon!
        </p>
        
        <div class="flex items-center justify-center gap-2 text-accent-400">
            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="font-medium">Please check back in a few minutes</span>
        </div>
    </div>
</div>
@endsection
