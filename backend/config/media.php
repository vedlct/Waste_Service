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

    /*
     * Editable metadata keys stored inside the media_assets.metadata JSON column.
     */
    'metadata_fields' => [
        'title' => ['label' => 'Title', 'max' => 160, 'help' => 'Optional internal title shown in pickers.'],
        'caption' => ['label' => 'Caption', 'max' => 500, 'help' => 'Optional caption rendered with the asset.'],
        'credit' => ['label' => 'Credit', 'max' => 160, 'help' => 'Photographer, agency, or source credit.'],
    ],

    /*
     * Tables that reference media_assets. Used to block deletion of media that is still in use.
     * Table and column names must be plain identifiers because they are inlined into SQL.
     */
    'usage_references' => [
        ['table' => 'media_assignments', 'column' => 'media_asset_id', 'label' => 'Media assignments'],
        ['table' => 'pages', 'column' => 'hero_media_id', 'label' => 'Page heroes'],
        ['table' => 'pages', 'column' => 'og_image_id', 'label' => 'Page share images'],
        ['table' => 'page_sections', 'column' => 'media_id', 'label' => 'Page sections'],
        ['table' => 'section_items', 'column' => 'media_id', 'label' => 'Section items'],
        ['table' => 'services', 'column' => 'hero_media_id', 'label' => 'Service heroes'],
        ['table' => 'service_content_blocks', 'column' => 'media_id', 'label' => 'Service content blocks'],
        ['table' => 'service_block_items', 'column' => 'media_id', 'label' => 'Service block items'],
        ['table' => 'price_categories', 'column' => 'image_id', 'label' => 'Price categories'],
        ['table' => 'service_items', 'column' => 'image_id', 'label' => 'Service items'],
    ],
];
