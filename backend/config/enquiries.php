<?php

return [
    /*
     * Admin workflow for a contact enquiry. `responds` marks the statuses that mean the
     * customer has been dealt with, which is what stamps `responded_at`.
     */
    'statuses' => [
        'new' => [
            'label' => 'New',
            'badge' => 'role',
            'responds' => false,
            'description' => 'Just arrived. Nobody has picked it up yet.',
        ],
        'in_progress' => [
            'label' => 'In progress',
            'badge' => 'role',
            'responds' => false,
            'description' => 'Assigned and being worked on.',
        ],
        'responded' => [
            'label' => 'Responded',
            'badge' => 'active',
            'responds' => true,
            'description' => 'The customer has had a reply.',
        ],
        'closed' => [
            'label' => 'Closed',
            'badge' => 'muted',
            'responds' => true,
            'description' => 'Finished, whether or not it became a booking.',
        ],
        'spam' => [
            'label' => 'Spam',
            'badge' => 'muted',
            'responds' => false,
            'description' => 'Junk submission. Kept so the same sender can be recognised.',
        ],
    ],

    'sources' => [
        'website' => 'Website form',
        'phone' => 'Phone call',
        'email' => 'Email',
        'manual' => 'Added by admin',
    ],

    /*
     * The service picker on the public contact form. Keys are what the frontend posts;
     * `slug` links the enquiry to a real service record where one exists.
     * Keep this in step with `frontend/src/components/contactUs/ContactUs.jsx`.
     */
    'service_options' => [
        'house' => ['label' => 'House clearance', 'slug' => 'house-clearance'],
        'garden' => ['label' => 'Garden clearance', 'slug' => 'garden-clearance'],
        'office' => ['label' => 'Office clearance', 'slug' => 'office-waste-clearance'],
        'builders' => ['label' => 'Builders waste removal', 'slug' => 'builders-waste-removal'],
        'other' => ['label' => 'Other service', 'slug' => null],
    ],

    /*
     * Public submit limits, mirroring the validation the frontend already applies.
     */
    'submit' => [
        'name' => ['min' => 2, 'max' => 100],
        'phone' => ['min' => 7, 'max' => 30],
        'email' => ['max' => 150],
        'message' => ['min' => 10, 'max' => 2000],
        'honeypot_field' => 'company',
        'rate_limit_per_minute' => 5,
    ],
];
