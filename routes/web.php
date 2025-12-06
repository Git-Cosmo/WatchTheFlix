<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ForumManagementController;

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
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Media Management
    Route::resource('media', MediaManagementController::class);
    
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
    Route::get('/forum', [ForumManagementController::class, 'index'])->name('forum.index');
    Route::get('/forum/create', [ForumManagementController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumManagementController::class, 'store'])->name('forum.store');
    Route::get('/forum/{category}/edit', [ForumManagementController::class, 'edit'])->name('forum.edit');
    Route::put('/forum/{category}', [ForumManagementController::class, 'update'])->name('forum.update');
    Route::delete('/forum/{category}', [ForumManagementController::class, 'destroy'])->name('forum.destroy');
});
