<?php

namespace App\Console\Commands;

use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\Media;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemap for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Add static pages
        $sitemap->add(Url::create('/')
            ->setLastModificationDate(now())
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        $sitemap->add(Url::create('/media')
            ->setLastModificationDate(now())
            ->setPriority(0.9)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        $sitemap->add(Url::create('/tv-guide')
            ->setLastModificationDate(now())
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        $sitemap->add(Url::create('/forum')
            ->setLastModificationDate(now())
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // Add media pages
        Media::published()->chunk(100, function ($mediaItems) use ($sitemap) {
            foreach ($mediaItems as $media) {
                $sitemap->add(Url::create("/media/{$media->id}")
                    ->setLastModificationDate($media->updated_at)
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        });

        // Add forum categories
        ForumCategory::all()->each(function ($category) use ($sitemap) {
            $sitemap->add(Url::create("/forum/category/{$category->slug}")
                ->setLastModificationDate($category->updated_at)
                ->setPriority(0.6)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        });

        // Add forum threads
        ForumThread::chunk(100, function ($threads) use ($sitemap) {
            foreach ($threads as $thread) {
                $sitemap->add(Url::create("/forum/thread/{$thread->slug}")
                    ->setLastModificationDate($thread->updated_at)
                    ->setPriority(0.5)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at public/sitemap.xml');

        return Command::SUCCESS;
    }
}
