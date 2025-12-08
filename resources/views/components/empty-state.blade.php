@props(['icon' => '📭', 'title' => 'Nothing here yet', 'message' => '', 'actionText' => null, 'actionUrl' => null])

<div {{ $attributes->merge(['class' => 'text-center py-16 px-4']) }}>
    <div class="text-6xl mb-4">{{ $icon }}</div>
    <h3 class="text-2xl font-bold text-dark-200 mb-2">{{ $title }}</h3>
    @if($message)
    <p class="text-dark-400 mb-6 max-w-md mx-auto">{{ $message }}</p>
    @endif
    
    @if($actionText && $actionUrl)
    <a href="{{ $actionUrl }}" class="btn-primary inline-block">
        {{ $actionText }}
    </a>
    @endif

    {{ $slot }}
</div>
