## Laravel RBAC

Laravel RBAC provides declarative, enum-backed role-based access control on top of `spatie/laravel-permission`. Define permissions as string-backed PHP enums, define roles in code, and synchronize them with Artisan.

### Prerequisites

- Install and migrate `spatie/laravel-permission` before running `rbac:reset`.
- Publish and configure this package with `php artisan vendor:publish --tag=rbac-config`.

### Abilities

Store string-backed ability enums in `config('rbac.path')`, which defaults to `app/Abilities`. Every enum case value must be unique across all discovered ability enums.

Create an ability enum with:

@verbatim
<code-snippet name="Create an ability enum" lang="shell">
php artisan make:ability PostAbility
</code-snippet>

<code-snippet name="Define abilities" lang="php">
<?php

namespace App\Abilities;

enum PostAbility: string
{
    case ViewPost = 'view post';
    case CreatePost = 'create post';
    case UpdatePost = 'update post';
    case DeletePost = 'delete post';
}
</code-snippet>
@endverbatim

Use enum cases, rather than duplicating permission strings, when defining roles.

### Defined roles

Create roles by extending `BinaryCats\LaravelRbac\DefinedRole`. Declare each guard in `$guards` and add a public method named for that guard which returns its permissions. Register every defined-role class in the `roles` array of `config/rbac.php`.

Create a role with:

@verbatim
<code-snippet name="Create a defined role" lang="shell">
php artisan make:role EditorRole
</code-snippet>

<code-snippet name="Define a role" lang="php">
<?php

namespace App\Roles;

use App\Abilities\PostAbility;
use BinaryCats\LaravelRbac\DefinedRole;

class EditorRole extends DefinedRole
{
    protected array $guards = ['web'];

    public function web(): array
    {
        return [
            PostAbility::ViewPost,
            PostAbility::CreatePost,
            PostAbility::UpdatePost,
        ];
    }
}
</code-snippet>

<code-snippet name="Register defined roles" lang="php">
// config/rbac.php
'roles' => [
    App\Roles\EditorRole::class,
],
</code-snippet>
@endverbatim

By default, a role's name is derived from its class name by removing the `Role` suffix. Set the protected `$name` property when the role needs an explicit name.

### Synchronizing RBAC

Run the reset command after changing abilities or role definitions:

@verbatim
<code-snippet name="Synchronize RBAC" lang="shell">
php artisan rbac:reset
</code-snippet>
@endverbatim

The default reset sequence flushes Spatie's permission cache, creates discovered ability permissions for Laravel's default authentication guard, and synchronizes the configured defined roles. For a custom guard, include it in `$guards` and provide a matching role method; role synchronization can create the permissions it assigns for that guard.
