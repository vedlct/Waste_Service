<?php

return [
    /*
     * Review moderation workflow. `publishes` marks the single status that makes a review
     * public, which is what the Phase 12 reviews endpoint will filter on.
     */
    'statuses' => [
        'pending' => [
            'label' => 'Pending',
            'badge' => 'role',
            'publishes' => false,
            'description' => 'Waiting for a moderator. Never shown on the website.',
        ],
        'published' => [
            'label' => 'Published',
            'badge' => 'active',
            'publishes' => true,
            'description' => 'Approved and live on the website.',
        ],
        'rejected' => [
            'label' => 'Rejected',
            'badge' => 'muted',
            'publishes' => false,
            'description' => 'Reviewed and declined. Kept for the record.',
        ],
        'spam' => [
            'label' => 'Spam',
            'badge' => 'muted',
            'publishes' => false,
            'description' => 'Junk submission. Kept so the same sender can be recognised.',
        ],
    ],

    'sources' => [
        'website' => 'Website form',
        'google' => 'Google',
        'trustpilot' => 'Trustpilot',
        'facebook' => 'Facebook',
        'manual' => 'Added by admin',
    ],

    'max_rating' => 5,

    /*
     * Public "write a review" form. Submissions always land as pending, so nothing a
     * visitor types reaches the website until a moderator publishes it.
     */
    'submit' => [
        'name' => ['min' => 2, 'max' => 100],
        'body' => ['min' => 10, 'max' => 2000],
        'honeypot_field' => 'website',
        'rate_limit_per_minute' => 3,
    ],
];
