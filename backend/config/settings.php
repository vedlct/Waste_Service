<?php

return [
    /*
     * Admin presentation for each site settings group. Groups found in the database but
     * missing here are still shown, using a humanised key and the fallback icon.
     */
    'groups' => [
        'general' => [
            'label' => 'General',
            'icon' => 'sliders',
            'description' => 'Site identity values used across the website and admin panel.',
        ],
        'contact' => [
            'label' => 'Contact',
            'icon' => 'telephone',
            'description' => 'Phone, email, location, and opening hours shown to customers.',
        ],
        'booking' => [
            'label' => 'Booking',
            'icon' => 'calendar-check',
            'description' => 'Checkout surcharges and fees. Amounts are entered in pounds and stored in pence.',
        ],
        'seo' => [
            'label' => 'SEO',
            'icon' => 'search',
            'description' => 'Default search metadata, the default share image, and search engine verification codes.',
        ],
        'business' => [
            'label' => 'Business Listing',
            'icon' => 'shop',
            'description' => 'Address and hours that search engines read from the site to build the business listing.',
        ],
        'social' => [
            'label' => 'Social',
            'icon' => 'share',
            'description' => 'Social profile links rendered in the website footer.',
        ],
        'notifications' => [
            'label' => 'Notifications',
            'icon' => 'bell',
            'description' => 'Who is emailed when something arrives from the website. Keep these private; they are never needed by the public site.',
        ],
    ],

    'fallback_icon' => 'gear',
];
