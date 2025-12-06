<?php $__env->startSection('title', $media->title); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Media Header -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div>
            <?php if($media->poster_url): ?>
            <img src="<?php echo e($media->poster_url); ?>" alt="<?php echo e($media->title); ?>" class="w-full rounded-lg shadow-lg">
            <?php else: ?>
            <div class="w-full h-96 bg-dark-800 rounded-lg flex items-center justify-center">
                <span class="text-dark-500 text-6xl">🎬</span>
            </div>
            <?php endif; ?>
        </div>

        <div class="lg:col-span-2">
            <h1 class="text-4xl font-bold mb-4"><?php echo e($media->title); ?></h1>
            
            <div class="flex flex-wrap gap-4 mb-6 text-sm text-dark-300">
                <?php if($media->release_year): ?>
                <span><?php echo e($media->release_year); ?></span>
                <?php endif; ?>
                <?php if($media->runtime): ?>
                <span>• <?php echo e($media->runtime); ?> min</span>
                <?php endif; ?>
                <?php if($media->rating): ?>
                <span>• <?php echo e($media->rating); ?></span>
                <?php endif; ?>
                <span>• <?php echo e(ucfirst($media->type)); ?></span>
            </div>

            <?php if($media->imdb_rating): ?>
            <div class="mb-6">
                <span class="text-accent-500 text-xl">⭐ <?php echo e($media->imdb_rating); ?>/10</span>
                <span class="text-dark-400 text-sm ml-2">(<?php echo e($media->ratings_count); ?> ratings)</span>
            </div>
            <?php endif; ?>

            <?php if($media->description): ?>
            <p class="text-dark-300 mb-6"><?php echo e($media->description); ?></p>
            <?php endif; ?>

            <!-- Actions -->
            <div class="flex flex-wrap gap-4 mb-6">
                <?php if(auth()->guard()->check()): ?>
                <form method="POST" action="<?php echo e(route('watchlist.add', $media)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-secondary">Add to Watchlist</button>
                </form>

                <form method="POST" action="<?php echo e(route('media.favorite', $media)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-secondary">Favorite</button>
                </form>
                <?php endif; ?>

                <?php if($media->stream_url && (!$media->requires_real_debrid || (auth()->check() && auth()->user()->hasRealDebridAccess()))): ?>
                <a href="<?php echo e($media->stream_url); ?>" class="btn-primary" target="_blank">Play</a>
                <?php elseif($media->requires_real_debrid): ?>
                <span class="btn-secondary opacity-50 cursor-not-allowed">Requires Real-Debrid</span>
                <?php endif; ?>
            </div>

            <?php if($media->genres): ?>
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Genres</h3>
                <div class="flex flex-wrap gap-2">
                    <?php $__currentLoopData = $media->genres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="px-3 py-1 bg-dark-800 rounded-full text-sm"><?php echo e($genre); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if($media->platforms && $media->platforms->isNotEmpty()): ?>
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Available On</h3>
                <div class="flex flex-wrap gap-3">
                    <?php $__currentLoopData = $media->platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($platform->website_url); ?>" target="_blank" 
                       class="flex items-center gap-2 px-4 py-2 bg-dark-800 hover:bg-dark-700 rounded-lg transition-colors border border-dark-700 hover:border-accent-500">
                        <?php if($platform->logo_url): ?>
                            <img src="<?php echo e($platform->logo_url); ?>" alt="<?php echo e($platform->name); ?>" class="w-5 h-5 object-contain">
                        <?php endif; ?>
                        <span class="text-sm font-medium"><?php echo e($platform->name); ?></span>
                        <?php if($platform->pivot->requires_subscription): ?>
                            <span class="text-xs text-dark-400">(Subscription)</span>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Rating Section -->
    <?php if(auth()->guard()->check()): ?>
    <div class="card p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4">Rate this <?php echo e($media->type); ?></h2>
        <form method="POST" action="<?php echo e(route('media.rate', $media)); ?>" class="flex gap-2">
            <?php echo csrf_field(); ?>
            <?php for($i = 1; $i <= 10; $i++): ?>
            <button type="submit" name="rating" value="<?php echo e($i); ?>" class="btn-secondary <?php echo e($userRating && $userRating->rating == $i ? 'bg-accent-500' : ''); ?>">
                <?php echo e($i); ?>

            </button>
            <?php endfor; ?>
        </form>
    </div>
    <?php endif; ?>

    <!-- Comments Section -->
    <div class="card p-6">
        <h2 class="text-2xl font-bold mb-6">Comments (<?php echo e($media->comments_count); ?>)</h2>

        <?php if(auth()->guard()->check()): ?>
        <form method="POST" action="<?php echo e(route('media.comment', $media)); ?>" class="mb-8">
            <?php echo csrf_field(); ?>
            <textarea name="content" rows="3" placeholder="Add a comment..." class="input-field w-full mb-4" required></textarea>
            <button type="submit" class="btn-primary">Post Comment</button>
        </form>
        <?php endif; ?>

        <div class="space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $media->comments->where('parent_id', null); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border-b border-dark-700 pb-4">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full bg-accent-500 flex items-center justify-center">
                        <span class="text-sm font-medium"><?php echo e(substr($comment->user->name, 0, 1)); ?></span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="font-semibold"><?php echo e($comment->user->name); ?></span>
                            <span class="text-sm text-dark-400"><?php echo e($comment->created_at->diffForHumans()); ?></span>
                        </div>
                        <p class="text-dark-300"><?php echo e($comment->content); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-dark-400 py-8">No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/WatchTheFlix/WatchTheFlix/resources/views/media/show.blade.php ENDPATH**/ ?>