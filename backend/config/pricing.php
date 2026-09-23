<?php

return [
    /*
     * Pricing status workflow shared by service items, load packages, and extra charges.
     * `warning` marks statuses that must be reviewed before the price is trusted publicly.
     */
    'statuses' => [
        'confirmed' => [
            'label' => 'Confirmed',
            'badge' => 'active',
            'warning' => false,
            'description' => 'Price checked and signed off by the business.',
        ],
        'active' => [
            'label' => 'Active',
            'badge' => 'active',
            'warning' => false,
            'description' => 'Charge is live and applied at checkout.',
        ],
        'placeholder' => [
            'label' => 'Placeholder',
            'badge' => 'role',
            'warning' => true,
            'description' => 'Provisional price carried over from the old site. Confirm before launch.',
        ],
        'quote_required' => [
            'label' => 'Quote required',
            'badge' => 'muted',
            'warning' => false,
            'description' => 'No fixed price. The customer is quoted after the enquiry.',
        ],
    ],

    'charge_types' => [
        'fixed' => 'Fixed amount',
        'surcharge' => 'Surcharge',
        'callout_fee' => 'Callout fee',
        'variable' => 'Variable / quoted',
    ],

    'default_vat_basis_points' => 2000,
];
