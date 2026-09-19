<?php

return [
    /*
     * Components a service content block can render on the frontend. The key is stored in
     * `service_content_blocks.component`; the label is what admins pick from.
     */
    'block_components' => [
        'service_overview' => 'Service overview',
        'content_block' => 'Content block',
        'feature_list' => 'Feature list',
        'steps' => 'How it works steps',
        'gallery' => 'Image gallery',
        'pricing_highlight' => 'Pricing highlight',
        'faq' => 'FAQ list',
        'cta' => 'Call to action',
    ],

    'default_block_component' => 'content_block',
];
