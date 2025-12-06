<?php $__env->startSection('title', 'TV Guide'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold mb-8 text-center">TV Guide</h1>
        
        <div class="bg-dark-800 rounded-lg p-8 mb-8">
            <p class="text-dark-300 text-center mb-8">
                Browse TV channels and programs from the UK and US. Stay up to date with what's currently airing and what's coming up next.
            </p>
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- UK TV Guide -->
                <a href="<?php echo e(route('tv-guide.channels', 'uk')); ?>" class="group">
                    <div class="bg-dark-700 rounded-lg p-8 text-center hover:bg-dark-600 transition-colors border-2 border-transparent hover:border-accent-500">
                        <div class="text-6xl mb-4">🇬🇧</div>
                        <h2 class="text-2xl font-bold mb-2 text-accent-400">UK TV Guide</h2>
                        <p class="text-dark-300">BBC, ITV, Channel 4, and more</p>
                    </div>
                </a>
                
                <!-- US TV Guide -->
                <a href="<?php echo e(route('tv-guide.channels', 'us')); ?>" class="group">
                    <div class="bg-dark-700 rounded-lg p-8 text-center hover:bg-dark-600 transition-colors border-2 border-transparent hover:border-accent-500">
                        <div class="text-6xl mb-4">🇺🇸</div>
                        <h2 class="text-2xl font-bold mb-2 text-accent-400">US TV Guide</h2>
                        <p class="text-dark-300">ABC, CBS, NBC, FOX, and more</p>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Search Section -->
        <div class="bg-dark-800 rounded-lg p-6">
            <h3 class="text-xl font-bold mb-4">Search TV Programs</h3>
            <form action="<?php echo e(route('tv-guide.search')); ?>" method="GET" class="flex gap-4">
                <input 
                    type="text" 
                    name="query" 
                    placeholder="Search for a program..." 
                    class="flex-1 bg-dark-700 border border-dark-600 rounded-lg px-4 py-2 text-dark-100 focus:border-accent-500 focus:outline-none"
                    required
                >
                <select name="country" class="bg-dark-700 border border-dark-600 rounded-lg px-4 py-2 text-dark-100 focus:border-accent-500 focus:outline-none">
                    <option value="">All Countries</option>
                    <option value="uk">UK</option>
                    <option value="us">US</option>
                </select>
                <button type="submit" class="btn-primary">Search</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/WatchTheFlix/WatchTheFlix/resources/views/tv-guide/index.blade.php ENDPATH**/ ?>