---
name: laravel-rbac-development
description: Build and maintain enum-backed abilities and code-defined roles with binary-cats/laravel-rbac. Use when creating abilities, defining roles, configuring guards, registering roles, or synchronizing RBAC with rbac:reset.
---

# Laravel RBAC Development

## When to use this skill

Use this skill when working with `binary-cats/laravel-rbac` abilities, `DefinedRole` classes, the `rbac` configuration, guards, or the `rbac:reset` command.

This skill covers this package's declarative RBAC workflow. For runtime authorization checks, policies, middleware, Blade directives, teams, and user-role assignment, use the `spatie/laravel-permission` guidance.

## Prerequisites

- Install and migrate `spatie/laravel-permission` before running `rbac:reset`.
- Publish the Laravel RBAC configuration when it has not already been published:

```shell
php artisan vendor:publish --tag=rbac-config
```

## Define abilities

Store abilities as string-backed PHP enums in `config('rbac.path')`, which defaults to `app/Abilities`. All discovered enum case values must be unique, including values in different ability enums.

Generate an ability enum:

```shell
php artisan make:ability PostAbility
```

Define permissions as enum cases and use their cases in role definitions instead of repeating string values:

```php
<?php

namespace App\Abilities;

enum PostAbility: string
{
    case ViewPost = 'view post';
    case CreatePost = 'create post';
    case UpdatePost = 'update post';
    case DeletePost = 'delete post';
}
```

## Define roles

Create each role by extending `BinaryCats\LaravelRbac\DefinedRole`. List its guards in `$guards`, then implement one public method per guard. Each guard method returns the permissions for that role and guard.

Generate a role:

```shell
php artisan make:role EditorRole
```

```php
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
```

Role names are derived from the class name by removing the `Role` suffix. Set the protected `$name` property when a role requires an explicit name.

For each configured guard, the role must provide a same-named public method. That method may return backed-enum cases or permission strings.

## Register and synchronize

Register every defined-role class in `config/rbac.php`:

```php
'roles' => [
    App\Roles\EditorRole::class,
],
```

Run the synchronization command after changing ability enums, role definitions, or registered roles:

```shell
php artisan rbac:reset
```

The default sequence flushes Spatie's permission cache, creates permissions for discovered abilities using Laravel's default authentication guard, and synchronizes all configured defined roles. A role can use custom guards by listing them in `$guards` and implementing matching methods; synchronization creates permissions assigned to that custom-guard role as needed.
