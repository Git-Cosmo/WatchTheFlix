@props(['type' => 'card', 'count' => 1])

@if($type === 'card')
    @for($i = 0; $i < $count; $i++)
    <div class="card overflow-hidden animate-pulse">
        <div class="w-full h-64 bg-dark-700"></div>
        <div class="p-4">
            <div class="h-4 bg-dark-700 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-dark-700 rounded w-1/2"></div>
        </div>
    </div>
    @endfor
@elseif($type === 'list')
    @for($i = 0; $i < $count; $i++)
    <div class="flex items-center space-x-4 p-4 animate-pulse">
        <div class="w-16 h-16 bg-dark-700 rounded"></div>
        <div class="flex-1 space-y-2">
            <div class="h-4 bg-dark-700 rounded w-3/4"></div>
            <div class="h-3 bg-dark-700 rounded w-1/2"></div>
        </div>
    </div>
    @endfor
@elseif($type === 'text')
    @for($i = 0; $i < $count; $i++)
    <div class="animate-pulse space-y-2 mb-4">
        <div class="h-4 bg-dark-700 rounded w-full"></div>
        <div class="h-4 bg-dark-700 rounded w-5/6"></div>
        <div class="h-4 bg-dark-700 rounded w-4/6"></div>
    </div>
    @endfor
@elseif($type === 'table')
    @for($i = 0; $i < $count; $i++)
    <tr class="animate-pulse">
        <td class="px-6 py-4"><div class="h-4 bg-dark-700 rounded w-24"></div></td>
        <td class="px-6 py-4"><div class="h-4 bg-dark-700 rounded w-32"></div></td>
        <td class="px-6 py-4"><div class="h-4 bg-dark-700 rounded w-20"></div></td>
        <td class="px-6 py-4"><div class="h-4 bg-dark-700 rounded w-16"></div></td>
    </tr>
    @endfor
@endif

<style>
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
