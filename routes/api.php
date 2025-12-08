<?php

use App\Http\Controllers\Api\XtreamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Xtream Codes API Routes
Route::prefix('xtream')->group(function () {
    // Main player_api.php endpoint (Xtream Codes compatible)
    Route::get('/player_api.php', [XtreamController::class, 'playerApi']);
    Route::post('/player_api.php', [XtreamController::class, 'playerApi']);
    
    // Alternative endpoint format
    Route::get('/panel_api.php', [XtreamController::class, 'playerApi']);
    Route::post('/panel_api.php', [XtreamController::class, 'playerApi']);
    
    // M3U playlist generation
    Route::get('/get.php', [XtreamController::class, 'getM3U']);
    Route::get('/playlist.m3u', [XtreamController::class, 'getM3U']);
    Route::get('/playlist.m3u8', [XtreamController::class, 'getM3U']);
    
    // EPG XML generation
    Route::get('/xmltv.php', [XtreamController::class, 'getEPG']);
    Route::get('/epg.xml', [XtreamController::class, 'getEPG']);
    Route::get('/xmltv', [XtreamController::class, 'xmltv']);
    
    // Stream URLs (Xtream format)
    Route::get('/live/{username}/{password}/{streamId}.{extension}', [XtreamController::class, 'getLiveStream']);
    Route::get('/live/{username}/{password}/{streamId}', [XtreamController::class, 'getLiveStream']);
    
    Route::get('/vod/{username}/{password}/{streamId}.{extension}', [XtreamController::class, 'getVodStream']);
    Route::get('/vod/{username}/{password}/{streamId}', [XtreamController::class, 'getVodStream']);
    
    Route::get('/series/{username}/{password}/{streamId}.{extension}', [XtreamController::class, 'getVodStream']);
    Route::get('/series/{username}/{password}/{streamId}', [XtreamController::class, 'getVodStream']);
    
    // Server info
    Route::get('/server_info', [XtreamController::class, 'serverInfo']);
    Route::post('/server_info', [XtreamController::class, 'serverInfo']);
});
