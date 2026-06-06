<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Spatie Permission Settings
    |--------------------------------------------------------------------------
    |
    | These settings configure the behavior of the Spatie Laravel Permission
    | package. You can configure the models, tables, and various other
    | options that the package uses to manage roles and permissions.
    |
    */

    'models' => [

        /*
        |----------------------------------------------------------------------
        | Permission Model
        |----------------------------------------------------------------------
        |
        | When using the "HasPermissions" trait from this package, we need to
        | know which Eloquent model should be used to retrieve your permissions.
        | You may set this to your own custom model if you prefer.
        |
        */

        'permission' => Spatie\Permission\Models\Permission::class,

        /*
        |----------------------------------------------------------------------
        | Role Model
        |----------------------------------------------------------------------
        |
        | When using the "HasRoles" trait from this package, we need to know
        | which Eloquent model should be used to retrieve your roles. You may
        | set this to your own custom model if you prefer.
        |
        */

        'role' => Spatie\Permission\Models\Role::class,

    ],

    'table_names' => [

        /*
        |----------------------------------------------------------------------
        | Users Table
        |----------------------------------------------------------------------
        |
        | When using the "HasRoles" trait from this package, we need to know
        | which table should be used to retrieve your users. You may set
        | this to your own custom table if you prefer.
        |
        */

        'users' => 'users',

        /*
        |----------------------------------------------------------------------
        | Roles Table
        |----------------------------------------------------------------------
        |
        | When using the "HasRoles" trait from this package, we need to know
        | which table should be used to retrieve your roles. You may set
        | this to your own custom table if you prefer.
        |
        */

        'roles' => 'roles',

        /*
        |----------------------------------------------------------------------
        | Permissions Table
        |----------------------------------------------------------------------
        |
        | When using the "HasPermissions" trait from this package, we need to
        | know which table should be used to retrieve your permissions. You
        | may set this to your own custom table if you prefer.
        |
        */

        'permissions' => 'permissions',

        /*
        |----------------------------------------------------------------------
        | Model Has Permissions Table
        |----------------------------------------------------------------------
        |
        | When using the "HasPermissions" trait from this package, we need to
        | know which pivot table should be used to retrieve your models that
        | have permissions. You may set this to your own custom table.
        |
        */

        'model_has_permissions' => 'model_has_permissions',

        /*
        |----------------------------------------------------------------------
        | Model Has Roles Table
        |----------------------------------------------------------------------
        |
        | When using the "HasRoles" trait from this package, we need to know
        | which pivot table should be used to retrieve your models that have
        | roles. You may set this to your own custom table.
        |
        */

        'model_has_roles' => 'model_has_roles',

        /*
        |----------------------------------------------------------------------
        | Role Has Permissions Table
        |----------------------------------------------------------------------
        |
        | When using the "HasRoles" trait from this package, we need to know
        | which pivot table should be used to retrieve the permissions that
        | are assigned to roles. You may set this to your own custom table.
        |
        */

        'role_has_permissions' => 'role_has_permissions',

    ],

    /*
    |--------------------------------------------------------------------------
    | Column Names
    |--------------------------------------------------------------------------
    |
    | Change these if you want to use different column names in your database
    | tables for the model morph keys.
    |
    */

    'column_names' => [

        /*
        |----------------------------------------------------------------------
        | Model Morph Key
        |----------------------------------------------------------------------
        |
        | When using the "HasRoles" or "HasPermissions" trait, the related model
        | is resolved using the model's morph key. You may customize the name
        | of this morph key column if you prefer.
        |
        */

        'model_morph_key' => 'model_id',

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | By default all permissions will be cached for 24 hours unless otherwise
    | specified. You may disable this by setting the cache expiration time
    | to 0 or by using a negative number. The cache key can also be customized.
    |
    */

    'cache' => [

        /*
        |----------------------------------------------------------------------
        | Cache Expiration Time
        |----------------------------------------------------------------------
        |
        | Set the cache expiration time in minutes. Setting this to 0 will
        | disable the caching entirely.
        |
        */

        'expiration_time' => 60 * 24,

        /*
        |----------------------------------------------------------------------
        | Cache Key
        |----------------------------------------------------------------------
        |
        | The cache key used to store permissions. You may change this if
        | you'd like to use a different key for your application.
        |
        */

        'key' => 'spatie.permission.cache',

        /*
        |----------------------------------------------------------------------
        | Cache Store
        |----------------------------------------------------------------------
        |
        | Specify the cache store to be used for caching permissions. This
        | should correspond to a store defined in your cache config.
        |
        */

        'store' => '',

    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions / Roles Guards
    |--------------------------------------------------------------------------
    |
    | By default, Spatie will use the default guard for checking permissions
    | and roles. You may specify which guards to use for specific operations.
    |
    */

    'use_guard_names' => true,

    /*
    |--------------------------------------------------------------------------
    | Register Permission and Role Guard
    |--------------------------------------------------------------------------
    |
    | If set to true, the package will register custom guard names for
    | permissions and roles using the guard names you have configured.
    |
    */

    'register_permission_check_method' => true,

    /*
    |--------------------------------------------------------------------------
    | Teams Feature
    |--------------------------------------------------------------------------
    |
    | If you want to use the teams feature, set this to true. The teams feature
    | allows you to associate permissions and roles with teams.
    |
    */

    'teams' => false,

];
