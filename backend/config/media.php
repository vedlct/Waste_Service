<?php

return [
    'disk' => env('MEDIA_DISK', 'public'),

    'paths' => [
        'library' => 'media/library',
        'pages' => 'media/pages',
        'services' => 'media/services',
        'settings' => 'media/settings',
        'temp' => 'media/tmp',
    ],

    'images' => [
        'max_size_kb' => 5120,
        'mimes' => ['jpg', 'jpeg', 'png', 'webp'],
        'mime_types' => ['image/jpeg', 'image/png', 'image/webp'],
        'max_width' => 3000,
        'max_height' => 3000,
    ],

    'files' => [
        'max_size_kb' => 10240,
        'mimes' => ['pdf'],
        'mime_types' => ['application/pdf'],
    ],
];
