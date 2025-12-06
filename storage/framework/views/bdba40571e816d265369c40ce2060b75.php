<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="mb-12">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Welcome to WatchTheFlix</h1>
        <p class="text-xl text-dark-300 mb-8">Stream your favorite movies and TV shows with Real-Debrid integration</p>
        
        <?php if(auth()->guard()->guest()): ?>
        <div class="flex space-x-4">
            <a href="<?php echo e(route('register')); ?>" class="btn-primary">Get Started</a>
            <a href="<?php echo e(route('login')); ?>" class="btn-secondary">Sign In</a>
        </div>
        <?php endif; ?>
    </div>

    <?php if(auth()->guard()->check()): ?>
    <!-- Featured Content -->
    <?php if($featured->count() > 0): ?>
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Featured</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('media.show', $item)); ?>" class="card overflow-hidden hover:border-accent-500 transition">
                <?php if($item->poster_url): ?>
                <img src="<?php echo e($item->poster_url); ?>" alt="<?php echo e($item->title); ?>" class="w-full h-64 object-cover">
                <?php else: ?>
                <div class="w-full h-64 bg-dark-800 flex items-center justify-center">
                    <span class="text-dark-500 text-4xl">🎬</span>
                </div>
                <?php endif; ?>
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2"><?php echo e($item->title); ?></h3>
                    <p class="text-sm text-dark-400"><?php echo e($item->release_year); ?> • <?php echo e(ucfirst($item->type)); ?></p>
                    <?php if($item->imdb_rating): ?>
                    <p class="text-sm text-accent-500 mt-2">⭐ <?php echo e($item->imdb_rating); ?>/10</p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Trending -->
    <?php if($trending->count() > 0): ?>
    <section>
        <h2 class="text-2xl font-bold mb-6">Trending Now</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php $__currentLoopData = $trending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('media.show', $item)); ?>" class="card overflow-hidden hover:border-accent-500 transition">
                <?php if($item->poster_url): ?>
                <img src="<?php echo e($item->poster_url); ?>" alt="<?php echo e($item->title); ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                <div class="w-full h-48 bg-dark-800 flex items-center justify-center">
                    <span class="text-dark-500 text-2xl">🎬</span>
                </div>
                <?php endif; ?>
                <div class="p-2">
                    <h3 class="text-sm font-semibold truncate"><?php echo e($item->title); ?></h3>
                    <p class="text-xs text-dark-400"><?php echo e($item->release_year); ?></p>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
    <?php endif; ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/WatchTheFlix/WatchTheFlix/resources/views/home.blade.php ENDPATH**/ ?>