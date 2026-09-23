<?php

return [
    /*
     * Which roles may open each admin module. Checked through the `access-module` gate on
     * every admin route and in the sidebar.
     *
     * A module missing from this list is denied to everyone, so a new module stays locked
     * until it is added here. `AdminAccessTest` fails if an admin route has no module.
     */
    'modules' => [
        // Content work: suitable for editors.
        'media' => ['super_admin', 'admin', 'editor'],
        'services' => ['super_admin', 'admin', 'editor'],
        'faqs' => ['super_admin', 'admin', 'editor'],
        'reviews' => ['super_admin', 'admin', 'editor'],
        'coverage' => ['super_admin', 'admin', 'editor'],
        'pages' => ['super_admin', 'admin', 'editor'],

        // Money and customer data: admins only.
        'pricing' => ['super_admin', 'admin'],
        'bookings' => ['super_admin', 'admin'],
        'enquiries' => ['super_admin', 'admin'],
        'settings' => ['super_admin', 'admin'],

        // Who can sign in, and what they did.
        'users' => ['super_admin'],
        'activity' => ['super_admin'],
    ],

    /*
     * How long the admin audit trail is kept before `model:prune` removes it.
     */
    'activity_retention_days' => (int) env('ACTIVITY_RETENTION_DAYS', 365),

    /*
     * Admin routes every signed-in admin user can reach, whatever their role.
     */
    'open_routes' => [
        'admin.dashboard',
        'admin.profile.edit',
        'admin.profile.update',
        'admin.profile.password.update',
    ],
];
