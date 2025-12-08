<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trial Period Settings
    |--------------------------------------------------------------------------
    |
    | Configure trial period settings for new user subscriptions
    |
    */

    'trial_days' => env('TRIAL_DAYS', 7),
    'trial_max_extensions' => env('TRIAL_MAX_EXTENSIONS', 2),
    'trial_grace_period_days' => env('TRIAL_GRACE_PERIOD_DAYS', 3),
    
    /*
    |--------------------------------------------------------------------------
    | Activity Log Settings
    |--------------------------------------------------------------------------
    |
    | Settings for user activity logging
    |
    */

    'activity_log_retention_days' => env('ACTIVITY_LOG_RETENTION_DAYS', 90),
    'track_ip_address' => env('ACTIVITY_TRACK_IP', true),
    'track_user_agent' => env('ACTIVITY_TRACK_USER_AGENT', true),

];
