<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Support Chat Domain
    |--------------------------------------------------------------------------
    */

    'domain' => env('SC_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Support Chat Path
    |--------------------------------------------------------------------------
    */

    'path' => env('SC_PATH', 'support-chat'),

    /*
    |--------------------------------------------------------------------------
    | Support Chat Route Middleware
    |--------------------------------------------------------------------------
    */

    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | User Model and Resource
    |--------------------------------------------------------------------------
    */

    'user_model' => App\Models\User::class,
    'user_resource' => TTBooking\SupportChat\Http\Resources\UserResource::class,
    'user_cred_key' => env('SC_CRED_KEY', 'email'),
    'user_cred_seed' => env('SC_CRED_SEED'),

    /*
    |--------------------------------------------------------------------------
    | Nano ID sizes for database tables
    |--------------------------------------------------------------------------
    */

    'nanoid_size_rooms' => (int) env('SC_NS_ROOMS', env('SC_NANOID_SIZE')),
    'nanoid_size_messages' => (int) env('SC_NS_MESSAGES', env('SC_NANOID_SIZE')),

    /*
    |--------------------------------------------------------------------------
    | Disk used for attachments storage
    |--------------------------------------------------------------------------
    */

    'disk' => env('SC_DISK', 'support-chat'),

];
