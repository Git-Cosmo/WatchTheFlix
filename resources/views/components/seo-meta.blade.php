<!-- SEO Meta Tags -->
<title>{{ $title }} - {{ config('app.name') }}</title>
<meta name="description" content="{{ $description }}">
@if($keywords)
<meta name="keywords" content="{{ $keywords }}">
@endif

<!-- Canonical URL -->
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Open Graph Tags -->
<meta property="og:title" content="{{ $ogTags['title'] ?? $title }}">
<meta property="og:description" content="{{ $ogTags['description'] ?? $description }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
@if($imageUrl)
<meta property="og:image" content="{{ $imageUrl }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
@endif
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- Twitter Card Tags -->
<meta name="twitter:card" content="{{ $twitterTags['card'] ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $twitterTags['title'] ?? $title }}">
<meta name="twitter:description" content="{{ $twitterTags['description'] ?? $description }}">
@if($imageUrl)
<meta name="twitter:image" content="{{ $imageUrl }}">
@endif

<!-- Additional SEO Tags -->
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">

<!-- Schema.org structured data for movies/TV shows -->
@if($type === 'video.movie' || $type === 'video.tv_show')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "{{ $type === 'video.movie' ? 'Movie' : 'TVSeries' }}",
  "name": "{{ $title }}",
  "description": "{{ $description }}",
  @if($imageUrl)
  "image": "{{ $imageUrl }}",
  @endif
  "url": "{{ $canonicalUrl }}"
}
</script>
@endif
