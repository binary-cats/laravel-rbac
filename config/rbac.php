<?php

use App\Enums\Ability;

return [

    /*
    |--------------------------------------------------------------------------
    | Role base access reset control
    |--------------------------------------------------------------------------
    |
    | When running rbac:reset those commands will be executed in sequence
    |
    */

    'jobs' => [
        \BinaryCats\Rbac\Jobs\FlushPermissionCache::class,
        \BinaryCats\Rbac\Jobs\ResetPermissions::class,
        \BinaryCats\Rbac\Jobs\SyncDefinedRoles::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Role base access ability set
    |--------------------------------------------------------------------------
    |
    | Place your ability files in this folder, and they will be auto discovered
    |
    */
    'path' => app()->path('Abilities'),

    /*
    |--------------------------------------------------------------------------
    | Defined Roles
    |--------------------------------------------------------------------------
    |
    | Defined roles are immutable by users
    |
    */

    'roles' => [

    ],
];
