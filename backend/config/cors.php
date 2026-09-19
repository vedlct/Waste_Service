<?php

return [
    /*
     * The Next.js frontend is served from its own origin, so the versioned API has to
     * allow it explicitly. Set CORS_ALLOWED_ORIGINS to a comma separated list in
     * production; the default covers the local Next.js dev server.
     */
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000,http://127.0.0.1:3000')),
    ))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
