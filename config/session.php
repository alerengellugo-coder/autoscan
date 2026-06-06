<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | This option determines the default session driver that will be used on
    | every request. By default, Laravel is configured to use the file
    | session driver, which will work well for the majority of apps.
    |
    | Supported: "file", "cookie", "database", "apc",
    |            "memcached", "redis", "dynamodb", "array"
    |
    */

    'driver' => env('SESSION_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of minutes that you wish the session
    | to be allowed to remain idle before it expires. If the session
    | expires, new data cannot be written to the session anymore.
    |
    */

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Session File Location
    |--------------------------------------------------------------------------
    |
    | When using the "file" session driver, the session files are stored on
    | the local filesystem. Here is where you may specify a custom path
    | where these session files should be stored by your application.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Connection
    |--------------------------------------------------------------------------
    |
    | When using the "database" session driver, you may specify the database
    | connection that should be used to manage your sessions. This should
    | correspond to a connection in your "database" configuration options.
    |
    */

    'connection' => env('SESSION_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Session Table
    |--------------------------------------------------------------------------
    |
    | When using the "database" session driver, you may specify the table to
    | be used to manage sessions. A sensible default has been set for you
    | here; however, you are free to change this to another table name.
    |
    */

    'table' => env('SESSION_TABLE', 'sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Cache Store
    |--------------------------------------------------------------------------
    |
    | While using one of the framework's cache driven session backends you
    | may list a cache store that should be used for these sessions. This
    | value will default to your application's default cache store.
    |
    */

    'store' => env('SESSION_STORE'),

    /*
    |--------------------------------------------------------------------------
    | Session Sweeping
    |--------------------------------------------------------------------------
    |
    | Some session drivers must be manually swept. Here you may specify the
    | number of minutes that should pass before sessions are swept clean.
    | This option is off by default.
    |
    */

    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Session Cookie
    |--------------------------------------------------------------------------
    |
    | Here you may specify the cookie settings for the session. These are
    | the same settings used by the Illuminate\Cookie\Middleware\EncryptCookies
    | middleware, which will check for these settings before using them.
    |
    */

    'cookie' => env('SESSION_COOKIE', Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'),

    'path' => env('SESSION_PATH', '/'),

    'domain' => env('SESSION_DOMAIN'),

    'secure' => env('SESSION_SECURE_COOKIE'),

    'http_only' => true,

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Partitioned Session Cookie
    |--------------------------------------------------------------------------
    |
    | Some browsers enforce a "partitioned" cookie architecture where cookies
    | set by the top-level site are not available to cross-site subdomains.
    | If this is enabled, the session cookie will be sent with the "partitioned"
    | attribute. This is generally only useful for applications that use a
    | cross-domain authentication architecture such as OAuth2.
    |
    */

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
