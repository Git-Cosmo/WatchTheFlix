<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'WatchTheFlix')); ?> - <?php echo $__env->yieldContent('title', 'Home'); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-dark-950 text-dark-100">
    <!-- Navigation -->
    <nav class="bg-dark-900 border-b border-dark-700">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="<?php echo e(route('home')); ?>" class="text-2xl font-bold text-accent-500">
                        WatchTheFlix
                    </a>
                    
                    <?php if(auth()->guard()->check()): ?>
                    <div class="hidden md:flex space-x-4">
                        <a href="<?php echo e(route('home')); ?>" class="nav-link <?php echo e(request()->routeIs('home') ? 'nav-link-active' : ''); ?>">
                            Home
                        </a>
                        <a href="<?php echo e(route('media.index')); ?>" class="nav-link <?php echo e(request()->routeIs('media.*') ? 'nav-link-active' : ''); ?>">
                            Browse
                        </a>
                        <a href="<?php echo e(route('watchlist.index')); ?>" class="nav-link <?php echo e(request()->routeIs('watchlist.*') ? 'nav-link-active' : ''); ?>">
                            Watchlist
                        </a>
                        <a href="<?php echo e(route('forum.index')); ?>" class="nav-link <?php echo e(request()->routeIs('forum.*') ? 'nav-link-active' : ''); ?>">
                            Forum
                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewAny', App\Models\User::class)): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.*') ? 'nav-link-active' : ''); ?>">
                            Admin
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="flex items-center space-x-4">
                    <?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn-secondary">Login</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn-primary">Sign Up</a>
                    <?php else: ?>
                    <div class="relative">
                        <button onclick="toggleDropdown('user-menu')" class="flex items-center space-x-2 text-dark-300 hover:text-dark-100">
                            <div class="w-8 h-8 rounded-full bg-accent-500 flex items-center justify-center">
                                <span class="text-sm font-medium"><?php echo e(substr(auth()->user()->name, 0, 1)); ?></span>
                            </div>
                            <span><?php echo e(auth()->user()->name); ?></span>
                        </button>
                        
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-dark-800 border border-dark-700 rounded-lg shadow-lg z-10">
                            <a href="<?php echo e(route('profile.show')); ?>" class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-dark-100">Profile</a>
                            <a href="<?php echo e(route('profile.settings')); ?>" class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-dark-100">Settings</a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="block">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-dark-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if(session('success')): ?>
    <div class="bg-green-600 text-white px-4 py-3">
        <div class="container mx-auto">
            <?php echo e(session('success')); ?>

        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-600 text-white px-4 py-3">
        <div class="container mx-auto">
            <?php echo e(session('error')); ?>

        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Cookie Consent Banner -->
    <div id="cookie-consent" class="hidden fixed bottom-0 left-0 right-0 bg-dark-900 border-t border-dark-700 p-4 z-50">
        <div class="container mx-auto flex items-center justify-between">
            <p class="text-sm text-dark-300">
                This site uses minimal cookies to enhance your experience. By continuing to use this site, you accept our use of cookies.
            </p>
            <button onclick="acceptCookies()" class="btn-primary">
                Accept
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark-900 border-t border-dark-700 mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center text-dark-400">
                <p>&copy; <?php echo e(date('Y')); ?> WatchTheFlix. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH /home/runner/work/WatchTheFlix/WatchTheFlix/resources/views/layouts/app.blade.php ENDPATH**/ ?>