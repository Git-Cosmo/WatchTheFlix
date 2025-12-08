<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SeoMeta extends Component
{
    // Content type constants
    public const TYPE_WEBSITE = 'website';
    public const TYPE_VIDEO_MOVIE = 'video.movie';
    public const TYPE_VIDEO_TV_SHOW = 'video.tv_show';
    public const TYPE_ARTICLE = 'article';

    public string $title;
    public ?string $description;
    public ?string $keywords;
    public ?string $canonicalUrl;
    public ?string $imageUrl;
    public ?array $ogTags;
    public ?array $twitterTags;
    public string $type;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title,
        ?string $description = null,
        ?string $keywords = null,
        ?string $canonicalUrl = null,
        ?string $imageUrl = null,
        ?array $ogTags = null,
        ?array $twitterTags = null,
        string $type = self::TYPE_WEBSITE
    ) {
        $this->title = $title;
        $this->description = $description ?? config('app.name') . ' - Stream your favorite movies and TV shows';
        $this->keywords = $keywords;
        $this->canonicalUrl = $canonicalUrl ?? url()->current();
        $this->imageUrl = $imageUrl;
        $this->ogTags = $ogTags;
        $this->twitterTags = $twitterTags;
        $this->type = $type;
    }

    /**
     * Check if content is a video type (movie or TV show)
     */
    public function isVideoType(): bool
    {
        return in_array($this->type, [self::TYPE_VIDEO_MOVIE, self::TYPE_VIDEO_TV_SHOW]);
    }

    /**
     * Get Schema.org type based on content type
     */
    public function getSchemaType(): string
    {
        return match($this->type) {
            self::TYPE_VIDEO_MOVIE => 'Movie',
            self::TYPE_VIDEO_TV_SHOW => 'TVSeries',
            default => 'WebPage',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.seo-meta');
    }
}
