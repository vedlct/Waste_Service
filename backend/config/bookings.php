<?php

return [
    /*
     * Booking lifecycle. `stage` orders the workflow so the submitted/confirmed timestamps
     * can be derived from the status instead of being maintained by hand. `cancelled` sits
     * outside the ladder and keeps whatever timestamps it already had.
     */
    'statuses' => [
        'draft' => [
            'label' => 'Draft',
            'badge' => 'muted',
            'stage' => 0,
            'description' => 'Created but not paid for yet. Not a confirmed job.',
        ],
        'submitted' => [
            'label' => 'Submitted',
            'badge' => 'role',
            'stage' => 1,
            'description' => 'The customer has completed checkout. Needs confirming by the office.',
        ],
        'confirmed' => [
            // The customer is emailed when a booking moves into this status.
            'notifies_customer' => true,
            'label' => 'Confirmed',
            'badge' => 'active',
            'stage' => 2,
            'description' => 'Accepted by the office. The customer has been told it is going ahead.',
        ],
        'scheduled' => [
            // The customer is emailed when a booking moves into this status.
            'notifies_customer' => true,
            'label' => 'Scheduled',
            'badge' => 'active',
            'stage' => 3,
            'description' => 'A crew and slot are allocated.',
        ],
        'completed' => [
            'label' => 'Completed',
            'badge' => 'active',
            'stage' => 4,
            'description' => 'The collection has been done.',
        ],
        'cancelled' => [
            // The customer is emailed when a booking moves into this status.
            'notifies_customer' => true,
            'label' => 'Cancelled',
            'badge' => 'muted',
            'stage' => null,
            'description' => 'Called off. Kept for the record.',
        ],
    ],

    'payment_statuses' => [
        'unpaid' => ['label' => 'Unpaid', 'badge' => 'muted'],
        'deposit_paid' => ['label' => 'Deposit paid', 'badge' => 'role'],
        'paid' => ['label' => 'Paid', 'badge' => 'active'],
        'failed' => ['label' => 'Failed', 'badge' => 'muted'],
        'refunded' => ['label' => 'Refunded', 'badge' => 'muted'],
    ],

    /*
     * `arrival` and `now` are what the public collection form posts.
     * `starts_submitted` decides whether a new booking skips the draft stage: there is
     * nothing to pay online for a pay-on-arrival job, so it is submitted straight away.
     */
    'payment_options' => [
        'pay_now' => [
            'label' => 'Pay now',
            'form_value' => 'now',
            'starts_submitted' => false,
            'extra_charge_slug' => null,
        ],
        'pay_on_arrival' => [
            'label' => 'Pay on arrival',
            'form_value' => 'arrival',
            'starts_submitted' => true,
            'extra_charge_slug' => 'pay-on-arrival-callout-fee',
        ],
    ],

    'notice_minutes_options' => [30, 60],

    'saturday_extra_charge_slug' => 'saturday-collection',

    'line_types' => [
        'service_item' => 'Service item',
        'load_package' => 'Load package',
        'extra_charge' => 'Extra charge',
    ],

    /*
     * References are read out over the phone, so they stay short and use digits only
     * after the prefix: MT-2609-4821.
     */
    'reference' => [
        'prefix' => 'MT',
        'digits' => 4,
        'max_attempts' => 20,
    ],

    'submit' => [
        'max_items' => 40,
        'max_quantity_per_line' => 50,
        'rate_limit_per_minute' => 10,
        'honeypot_field' => 'company_website',
    ],
];
