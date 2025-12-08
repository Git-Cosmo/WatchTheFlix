<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stream Caching
    |--------------------------------------------------------------------------
    |
    | Enable Redis-based caching for stream data to achieve 10-100x
    | performance improvements.
    |
    */

    'cache_enabled' => env('STREAM_CACHE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Token Security
    |--------------------------------------------------------------------------
    |
    | Enhanced token security settings including IP binding and time limits
    |
    */

    'strict_ip_binding' => env('STREAM_STRICT_IP_BINDING', true),
    'token_lifetime' => env('STREAM_TOKEN_LIFETIME', 7200), // 2 hours in seconds
    
    /*
    |--------------------------------------------------------------------------
    | Connection Limits
    |--------------------------------------------------------------------------
    |
    | Maximum concurrent connections per user
    |
    */

    'max_connections_per_user' => env('STREAM_MAX_CONNECTIONS', 3),
    
    /*
    |--------------------------------------------------------------------------
    | Timeshift / Catch-up TV
    |--------------------------------------------------------------------------
    |
    | Enable timeshift functionality for rewinding live TV
    |
    */

    'enable_timeshift' => env('STREAM_ENABLE_TIMESHIFT', true),
    'timeshift_max_hours' => env('STREAM_TIMESHIFT_MAX_HOURS', 72), // 3 days
    
    /*
    |--------------------------------------------------------------------------
    | EPG Settings
    |--------------------------------------------------------------------------
    |
    | EPG reminder and notification settings
    |
    */

    'epg_reminder_default_minutes' => env('EPG_REMINDER_DEFAULT_MINUTES', 15),
    'epg_reminder_notification_methods' => ['in_app', 'email', 'push'],
    
    /*
    |--------------------------------------------------------------------------
    | WebSocket Broadcasting
    |--------------------------------------------------------------------------
    |
    | Real-time updates for stream connections and notifications
    |
    */

    'websocket_enabled' => env('WEBSOCKET_ENABLED', true),
    'websocket_driver' => env('BROADCAST_DRIVER', 'log'), // reverb, pusher, etc.
    
    /*
    |--------------------------------------------------------------------------
    | Transcoding Settings
    |--------------------------------------------------------------------------
    |
    | Adaptive bitrate transcoding settings with FFmpeg
    |
    */

    'transcoding' => [
        'enabled' => env('TRANSCODING_ENABLED', true),
        'preset' => env('TRANSCODING_PRESET', 'medium'), // ultrafast, fast, medium, slow
        'storage_path' => env('TRANSCODING_STORAGE_PATH', storage_path('app/transcoded')),
        'segment_duration' => env('TRANSCODING_SEGMENT_DURATION', 4), // seconds
        'qualities' => ['360p', '480p', '720p', '1080p', '4k'],
    ],
    
];
