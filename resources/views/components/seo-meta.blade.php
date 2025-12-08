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

<!-- Schema.org structured data -->
@php
$schemaType = match($type) {
    'video.movie' => 'Movie',
    'video.tv_show' => 'TVSeries',
    'article' => 'Article',
    default => 'WebPage',
};
@endphp

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "{{ $schemaType }}",
  "name": "{{ $title }}",
  "description": "{{ $description }}",
  @if($imageUrl)
  "image": {
    "@@type": "ImageObject",
    "url": "{{ $imageUrl }}",
    "width": "1200",
    "height": "630"
  },
  @endif
  "url": "{{ $canonicalUrl }}"@if($schemaType === 'WebPage'),
  "publisher": {
    "@@type": "Organization",
    "name": "{{ config('app.name') }}",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  }
  @endif
}
</script>

<!-- Breadcrumb structured data -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "{{ route('home') }}"
    }
  ]
}
</script>
