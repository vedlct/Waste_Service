<?php

return [
    /*
     * Content-Security-Policy for the admin and auth pages (the `web` middleware group).
     *
     * Inline <script> blocks carry a per-request nonce via the @nonce Blade directive, so an
     * injected script without it will not run. Inline style attributes are used throughout
     * the views, so styles keep 'unsafe-inline'.
     *
     * Set CSP_REPORT_ONLY=true to watch for violations in the browser console without
     * blocking anything, or CSP_ENABLED=false to switch the header off entirely.
     */
    'csp' => [
        'enabled' => (bool) env('CSP_ENABLED', true),
        'report_only' => (bool) env('CSP_REPORT_ONLY', false),

        'script_hosts' => [
            'https://code.jquery.com',
            'https://cdn.jsdelivr.net',
            'https://cdn.datatables.net',
            'https://cdnjs.cloudflare.com',
        ],

        'style_hosts' => [
            'https://cdn.jsdelivr.net',
            'https://cdn.datatables.net',
            'https://cdnjs.cloudflare.com',
        ],

        // Bootstrap Icons loads its font files from jsDelivr.
        'font_hosts' => [
            'https://cdn.jsdelivr.net',
        ],
    ],
];
