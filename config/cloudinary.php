<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration untuk Cloudinary API.
    |
    */

    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
    ],

    'url' => [
        'secure' => true,
    ],

    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary URL Configuration
    |--------------------------------------------------------------------------
    |
    | URL lengkap untuk Cloudinary, menggabungkan API key, secret, dan cloud name.
    |
    */
    'cloud_url' => env('CLOUDINARY_URL'),

    /**
     * Upload Preset dari Dashboard Cloudinary
     */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /**
     * Route untuk mendapatkan cloud_image_url dari Blade Upload Widget
     */
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),

    /**
     * Action Controller untuk mendapatkan cloud_image_url dari Blade Upload Widget
     */
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),
];
