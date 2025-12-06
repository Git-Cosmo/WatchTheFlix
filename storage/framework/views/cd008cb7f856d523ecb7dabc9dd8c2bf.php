<?php $__env->startSection('title', $country . ' TV Channels'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="<?php echo e(route('tv-guide.index')); ?>" class="text-accent-400 hover:text-accent-300">
            ← Back to TV Guide
        </a>
    </div>
    
    <h1 class="text-4xl font-bold mb-8"><?php echo e($country); ?> TV Channels</h1>
    
    <?php if($channels->isEmpty()): ?>
        <div class="bg-dark-800 rounded-lg p-8 text-center">
            <p class="text-dark-300">No TV channels available for <?php echo e($country); ?> at the moment.</p>
        </div>
    <?php else: ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('tv-guide.channel', $channel)); ?>" class="group">
                    <div class="bg-dark-800 rounded-lg p-6 hover:bg-dark-700 transition-colors border-2 border-transparent hover:border-accent-500">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-accent-400 group-hover:text-accent-300">
                                    <?php echo e($channel->name); ?>

                                </h3>
                                <?php if($channel->channel_number): ?>
                                    <p class="text-sm text-dark-400">Channel <?php echo e($channel->channel_number); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if($channel->logo_url): ?>
                                <img src="<?php echo e($channel->logo_url); ?>" alt="<?php echo e($channel->name); ?>" class="w-12 h-12 object-contain">
                            <?php endif; ?>
                        </div>
                        
                        <?php if($channel->description): ?>
                            <p class="text-dark-300 text-sm line-clamp-2">
                                <?php echo e($channel->description); ?>

                            </p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/WatchTheFlix/WatchTheFlix/resources/views/tv-guide/channels.blade.php ENDPATH**/ ?>