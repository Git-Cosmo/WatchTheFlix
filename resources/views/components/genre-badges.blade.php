@props(['genres', 'limit' => 2])

@if($genres && is_array($genres) && count($genres) > 0)
<div class="flex flex-wrap gap-1 pt-1">
    @foreach(array_slice($genres, 0, $limit) as $genre)
    <span class="text-xs px-2 py-0.5 bg-gh-bg-tertiary rounded-full text-gh-text-muted">{{ $genre }}</span>
    @endforeach
</div>
@endif
