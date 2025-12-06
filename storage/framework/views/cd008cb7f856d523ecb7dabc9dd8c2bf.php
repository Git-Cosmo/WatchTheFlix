<?php $__env->startSection('title', $country . ' TV Channels'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-12">
    <div class="mb-10">
        <a href="<?php echo e(route('tv-guide.index')); ?>" class="inline-flex items-center text-accent-400 hover:text-accent-300 transition-colors font-medium">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to TV Guide
        </a>
    </div>
    
    <h1 class="text-5xl md:text-6xl font-extrabold mb-12">
        <span class="bg-gradient-to-r from-accent-400 via-accent-500 to-accent-600 bg-clip-text text-transparent"><?php echo e($country); ?> TV Channels</span>
    </h1>
    
    <?php if($channels->isEmpty()): ?>
        <div class="card p-16 text-center">
            <div class="text-8xl mb-6">📺</div>
            <p class="text-gh-text-muted text-xl">No TV channels available for <?php echo e($country); ?> at the moment.</p>
        </div>
    <?php else: ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('tv-guide.channel', $channel)); ?>" class="group">
                    <div class="card p-6 hover:border-accent-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-accent-500/10 h-full">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white group-hover:text-accent-400 transition-colors mb-1">
                                    <?php echo e($channel->name); ?>

                                </h3>
                                <?php if($channel->channel_number): ?>
                                    <p class="text-sm text-gh-text-muted">Channel <?php echo e($channel->channel_number); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if($channel->logo_url): ?>
                                <img src="<?php echo e($channel->logo_url); ?>" alt="<?php echo e($channel->name); ?>" class="w-16 h-16 object-contain rounded-lg">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gh-bg-tertiary rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-3xl">📺</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($channel->description): ?>
                            <p class="text-gh-text-muted text-sm line-clamp-2 mb-4">
                                <?php echo e($channel->description); ?>

                            </p>
                        <?php endif; ?>
                        
                        <div class="inline-flex items-center text-accent-400 text-sm font-semibold mt-auto">
                            View schedule
                            <svg class="ml-1 h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/WatchTheFlix/WatchTheFlix/resources/views/tv-guide/channels.blade.php ENDPATH**/ ?>