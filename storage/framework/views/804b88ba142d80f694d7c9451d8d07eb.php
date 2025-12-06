<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-16">
    <div class="max-w-md mx-auto">
        <div class="card p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Login to WatchTheFlix</h2>
            
            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-dark-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required autofocus
                           class="input-field w-full <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-dark-300 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                           class="input-field w-full <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-dark-600 text-accent-500 focus:ring-accent-500">
                        <span class="ml-2 text-sm text-dark-300">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full">
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-dark-400">
                    Don't have an account? 
                    <a href="<?php echo e(route('register')); ?>" class="text-accent-500 hover:text-accent-400">Sign up</a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/runner/work/WatchTheFlix/WatchTheFlix/resources/views/auth/login.blade.php ENDPATH**/ ?>