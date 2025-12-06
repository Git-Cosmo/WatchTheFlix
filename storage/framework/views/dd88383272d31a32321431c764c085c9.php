<?php $__env->startSection('title', 'TV Guide'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-12">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-6xl font-extrabold mb-4">
                <span class="bg-gradient-to-r from-accent-400 via-accent-500 to-accent-600 bg-clip-text text-transparent">TV Guide</span>
            </h1>
            <p class="text-xl text-gh-text-muted max-w-2xl mx-auto">
                Browse TV channels and programs from the UK and US. Stay up to date with what's currently airing and what's coming up next.
            </p>
        </div>
        
        <!-- Country Selection Cards -->
        <div class="grid md:grid-cols-2 gap-8 mb-16">
            <!-- UK TV Guide -->
            <a href="<?php echo e(route('tv-guide.channels', 'uk')); ?>" class="group">
                <div class="card p-12 text-center hover:border-accent-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-accent-500/10">
                    <div class="text-8xl mb-8">🇬🇧</div>
                    <h2 class="text-4xl font-bold mb-4 text-white group-hover:text-accent-400 transition-colors">UK TV Guide</h2>
                    <p class="text-gh-text-muted text-lg mb-6">BBC, ITV, Channel 4, and more</p>
                    <div class="inline-flex items-center text-accent-400 font-semibold">
                        Browse channels
                        <svg class="ml-2 h-5 w-5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </div>
            </a>
            
            <!-- US TV Guide -->
            <a href="<?php echo e(route('tv-guide.channels', 'us')); ?>" class="group">
                <div class="card p-12 text-center hover:border-accent-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-accent-500/10">
                    <div class="text-8xl mb-8">🇺🇸</div>
                    <h2 class="text-4xl font-bold mb-4 text-white group-hover:text-accent-400 transition-colors">US TV Guide</h2>
                    <p class="text-gh-text-muted text-lg mb-6">ABC, CBS, NBC, FOX, and more</p>
                    <div class="inline-flex items-center text-accent-400 font-semibold">
                        Browse channels
                        <svg class="ml-2 h-5 w-5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Search Section -->
        <div class="card p-8">
            <div class="flex items-center mb-6">
                <svg class="h-7 w-7 text-accent-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="text-2xl font-bold text-white">Search TV Programs</h3>
            </div>
            <form action="<?php echo e(route('tv-guide.search')); ?>" method="GET" class="flex flex-col md:flex-row gap-4">
                <input 
                    type="text" 
                    name="query" 
                    placeholder="Search for a program..." 
                    class="flex-1 input-field"
                    required
                >
                <select name="country" class="input-field md:w-48">
                    <option value="">All Countries</option>
                    <option value="uk">UK</option>
                    <option value="us">US</option>
                </select>
                <button type="submit" class="btn-primary px-8">Search</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/WatchTheFlix/WatchTheFlix/resources/views/tv-guide/index.blade.php ENDPATH**/ ?>