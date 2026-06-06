<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server Side Rendering
    |--------------------------------------------------------------------------
    |
    | These are the SSR settings for Inertia.js. You may enable or disable
    | SSR, configure the URL that Inertia should use for server-side
    | rendering, and set the encryption key for the SSR bundle.
    |
    */

    'ssr' => [
        'enabled' => env('INERTIA_SSR_ENABLED', false),
        'url' => env('INERTIA_SSR_URL', 'http://127.0.0.1:13714'),
        'bundle' => base_path('bootstrap/ssr/ssr.mjs'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    |
    | These are the testing configuration options for Inertia.js. The page
    | and content options are used by the testing helpers to determine
    | what page and content should be asserted during tests.
    |
    */

    'testing' => [
        'ensure_pages_exist' => true,

        'page_paths' => [
            resource_path('js/Pages'),
        ],
    ],

];
