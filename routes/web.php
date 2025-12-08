<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ForumManagementController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\MediaManagementController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TvGuideController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

// Language Switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');

    // Two-Factor Authentication
    Route::prefix('two-factor')->name('two-factor.')->group(function () {
        Route::post('/enable', [\App\Http\Controllers\TwoFactorAuthController::class, 'enable'])->name('enable');
        Route::post('/confirm', [\App\Http\Controllers\TwoFactorAuthController::class, 'confirm'])->name('confirm');
        Route::post('/disable', [\App\Http\Controllers\TwoFactorAuthController::class, 'disable'])->name('disable');
        Route::get('/recovery-codes', [\App\Http\Controllers\TwoFactorAuthController::class, 'showRecoveryCodes'])->name('recovery-codes');
        Route::post('/recovery-codes/regenerate', [\App\Http\Controllers\TwoFactorAuthController::class, 'regenerateRecoveryCodes'])->name('recovery-codes.regenerate');
    });

    // Media
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::get('/media/{media}', [MediaController::class, 'show'])->name('media.show');
    Route::post('/media/{media}/favorite', [MediaController::class, 'toggleFavorite'])->name('media.favorite');
    Route::post('/media/{media}/rate', [MediaController::class, 'rate'])->name('media.rate');
    Route::post('/media/{media}/comment', [MediaController::class, 'comment'])->name('media.comment');
    Route::post('/media/{media}/react', [MediaController::class, 'react'])->name('media.react');

    // Watchlist
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/{media}', [WatchlistController::class, 'add'])->name('watchlist.add');
    Route::delete('/watchlist/{media}', [WatchlistController::class, 'remove'])->name('watchlist.remove');

    // Playlists
    Route::resource('playlists', \App\Http\Controllers\PlaylistController::class);
    Route::post('/playlists/{playlist}/add-media', [\App\Http\Controllers\PlaylistController::class, 'addMedia'])->name('playlists.add-media');
    Route::delete('/playlists/{playlist}/remove-media/{media}', [\App\Http\Controllers\PlaylistController::class, 'removeMedia'])->name('playlists.remove-media');
    Route::post('/playlists/{playlist}/reorder', [\App\Http\Controllers\PlaylistController::class, 'reorder'])->name('playlists.reorder');

    // Forum
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/category/{category:slug}', [ForumController::class, 'category'])->name('forum.category');
    Route::get('/forum/category/{category:slug}/create', [ForumController::class, 'createThread'])->name('forum.create-thread');
    Route::post('/forum/category/{category:slug}/create', [ForumController::class, 'storeThread'])->name('forum.store-thread');
    Route::get('/forum/thread/{thread:slug}', [ForumController::class, 'showThread'])->name('forum.thread');
    Route::post('/forum/thread/{thread:slug}/reply', [ForumController::class, 'replyToThread'])->name('forum.reply');
    Route::post('/forum/thread/{thread:slug}/subscribe', [ForumController::class, 'subscribe'])->name('forum.subscribe');
    Route::post('/forum/thread/{thread:slug}/pin', [ForumController::class, 'togglePin'])->name('forum.pin')->middleware('role:admin');
    Route::post('/forum/thread/{thread:slug}/lock', [ForumController::class, 'toggleLock'])->name('forum.lock')->middleware('role:admin');
    Route::delete('/forum/thread/{thread:slug}', [ForumController::class, 'destroy'])->name('forum.destroy');

    // TV Guide
    Route::get('/tv-guide', [TvGuideController::class, 'index'])->name('tv-guide.index');
    Route::get('/tv-guide/{country}', [TvGuideController::class, 'channels'])->name('tv-guide.channels');
    Route::get('/tv-guide/channel/{channel}', [TvGuideController::class, 'channel'])->name('tv-guide.channel');
    Route::get('/tv-guide/search', [TvGuideController::class, 'search'])->name('tv-guide.search');

    // Notifications
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Media Management
    Route::resource('media', MediaManagementController::class)->parameters(['media' => 'media']);
    
    // Subtitle Management
    Route::get('/media/{media}/subtitles', [\App\Http\Controllers\Admin\SubtitleController::class, 'index'])->name('media.subtitles');
    Route::post('/media/{media}/subtitles', [\App\Http\Controllers\Admin\SubtitleController::class, 'store'])->name('media.subtitles.store');
    Route::delete('/media/{media}/subtitles/{language}', [\App\Http\Controllers\Admin\SubtitleController::class, 'destroy'])->name('media.subtitles.destroy');

    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Invite Management
    Route::get('/invites', [InviteController::class, 'index'])->name('invites.index');
    Route::post('/invites', [InviteController::class, 'store'])->name('invites.store');
    Route::delete('/invites/{invite}', [InviteController::class, 'destroy'])->name('invites.destroy');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Forum Management
    Route::prefix('forum-management')->name('forum.')->group(function () {
        Route::get('/', [ForumManagementController::class, 'index'])->name('admin.index');
        Route::get('/create', [ForumManagementController::class, 'create'])->name('admin.create');
        Route::post('/', [ForumManagementController::class, 'store'])->name('admin.store');
        Route::get('/{category}/edit', [ForumManagementController::class, 'edit'])->name('admin.edit');
        Route::put('/{category}', [ForumManagementController::class, 'update'])->name('admin.update');
        Route::delete('/{category}', [ForumManagementController::class, 'destroy'])->name('admin.destroy');
    });

    // TMDB Import
    Route::prefix('tmdb-import')->name('tmdb-import.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TmdbImportController::class, 'index'])->name('index');
        Route::post('/search', [\App\Http\Controllers\Admin\TmdbImportController::class, 'search'])->name('search');
        Route::post('/import', [\App\Http\Controllers\Admin\TmdbImportController::class, 'import'])->name('import');
        Route::post('/bulk-import', [\App\Http\Controllers\Admin\TmdbImportController::class, 'bulkImport'])->name('bulk-import');
    });

    // Xtream Codes Management
    Route::prefix('xtream')->name('xtream.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'index'])->name('index');
        Route::get('/configuration', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'apiConfiguration'])->name('configuration');
        Route::get('/users', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'users'])->name('users');
        Route::get('/streams', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'streams'])->name('streams');
        Route::get('/statistics', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'statistics'])->name('statistics');
        Route::get('/documentation', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'documentation'])->name('documentation');
        
        Route::post('/users/{user}/generate-token', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'generateToken'])->name('generate-token');
        Route::delete('/users/{user}/revoke-token', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'revokeToken'])->name('revoke-token');
        Route::post('/test-endpoint', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'testEndpoint'])->name('test-endpoint');
        Route::get('/export-epg', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'exportEpg'])->name('export-epg');
        Route::get('/export-m3u/{user}', [\App\Http\Controllers\Admin\XtreamManagementController::class, 'exportM3u'])->name('export-m3u');
    });

    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');

    // Subscription Management
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SubscriptionManagementController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\SubscriptionManagementController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\SubscriptionManagementController::class, 'store'])->name('store');
        Route::get('/{plan}/edit', [\App\Http\Controllers\Admin\SubscriptionManagementController::class, 'edit'])->name('edit');
        Route::put('/{plan}', [\App\Http\Controllers\Admin\SubscriptionManagementController::class, 'update'])->name('update');
        Route::delete('/{plan}', [\App\Http\Controllers\Admin\SubscriptionManagementController::class, 'destroy'])->name('destroy');
        Route::get('/list', [\App\Http\Controllers\Admin\SubscriptionManagementController::class, 'subscriptions'])->name('list');
        Route::post('/assign/{user}', [\App\Http\Controllers\Admin\SubscriptionManagementController::class, 'assignSubscription'])->name('assign');
    });

    // Bouquet Management
    Route::prefix('bouquets')->name('bouquets.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BouquetManagementController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\BouquetManagementController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\BouquetManagementController::class, 'store'])->name('store');
        Route::get('/{bouquet}/edit', [\App\Http\Controllers\Admin\BouquetManagementController::class, 'edit'])->name('edit');
        Route::put('/{bouquet}', [\App\Http\Controllers\Admin\BouquetManagementController::class, 'update'])->name('update');
        Route::delete('/{bouquet}', [\App\Http\Controllers\Admin\BouquetManagementController::class, 'destroy'])->name('destroy');
    });

    // Activity Log
    Route::prefix('activity-log')->name('activity-log.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('export');
        Route::get('/stats', [\App\Http\Controllers\Admin\ActivityLogController::class, 'stats'])->name('stats');
    });

    // Transcoding Management
    Route::prefix('transcoding')->name('transcoding.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TranscodingController::class, 'index'])->name('index');
        Route::post('/queue/{media}', [\App\Http\Controllers\Admin\TranscodingController::class, 'queue'])->name('queue');
        Route::post('/{job}/process', [\App\Http\Controllers\Admin\TranscodingController::class, 'process'])->name('process');
        Route::delete('/{job}', [\App\Http\Controllers\Admin\TranscodingController::class, 'destroy'])->name('destroy');
        Route::post('/generate-playlist/{media}', [\App\Http\Controllers\Admin\TranscodingController::class, 'generatePlaylist'])->name('generate-playlist');
    });
});
