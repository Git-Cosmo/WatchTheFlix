@extends('layouts.app')

@section('title', 'Xtream API Documentation')

@section('content')
<div class="container mx-auto px-4 py-8">
    <x-xtream-hold-notice />
    
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">Xtream API Documentation</h1>
        <a href="{{ route('admin.xtream.index') }}" class="btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card p-8">
        <div class="prose prose-invert max-w-none">
            <div class="markdown-content">
                {!! \Illuminate\Support\Str::markdown($documentation) !!}
            </div>
        </div>
    </div>
</div>

<style>
.markdown-content h1 {
    @apply text-3xl font-bold mb-4 text-white;
}
.markdown-content h2 {
    @apply text-2xl font-bold mb-3 mt-6 text-accent-400;
}
.markdown-content h3 {
    @apply text-xl font-semibold mb-2 mt-4;
}
.markdown-content p {
    @apply mb-4 text-dark-300;
}
.markdown-content code {
    @apply bg-dark-800 px-2 py-1 rounded text-sm text-accent-400;
}
.markdown-content pre {
    @apply bg-dark-900 p-4 rounded-lg overflow-x-auto mb-4;
}
.markdown-content pre code {
    @apply bg-transparent p-0;
}
.markdown-content ul {
    @apply list-disc list-inside mb-4 space-y-1 text-dark-300;
}
.markdown-content ol {
    @apply list-decimal list-inside mb-4 space-y-1 text-dark-300;
}
.markdown-content a {
    @apply text-accent-400 hover:text-accent-300 underline;
}
.markdown-content table {
    @apply w-full border-collapse mb-4;
}
.markdown-content th {
    @apply bg-dark-800 px-4 py-2 text-left font-semibold;
}
.markdown-content td {
    @apply border border-dark-700 px-4 py-2;
}
</style>
@endsection
