---
name: laravel-rbac-development
description: Use Binary Cats Laravel RBAC to define enum-backed permissions, compose immutable application roles, configure guards, and synchronize them with spatie/laravel-permission.
---

# Laravel RBAC development

Use this package when working with `binary-cats/laravel-rbac`. It is an opinionated layer on top of `spatie/laravel-permission`: abilities are PHP backed enums, while roles are PHP classes that declare their permissions. The database is synchronized from those definitions with `rbac:reset`.

## Set up the package

Install the package, ensure the Spatie permission migrations have run, then publish the package config when it needs changing:

```bash
composer require binary-cats/laravel-rbac
php artisan vendor:publish --tag=rbac-config
php artisan migrate
```

`config/rbac.php` controls:

- `path`: the directory containing ability enum files; by default `app/Abilities`.
- `roles`: an array of `DefinedRole` class names that are managed by the package.
- `jobs`: the ordered work performed by `rbac:reset`. Preserve the normal order unless a customization specifically requires otherwise: flush Spatie's permission cache, store discovered permissions, then sync defined roles.

The command refuses to run until every table named by `permission.table_names` exists.

## Define abilities

Create abilities with the generator:

```bash
php artisan make:ability PostAbility
```

An ability must be a `BackedEnum` (normally `enum ...: string`) in the configured ability path. Use stable, human-readable string values because those values become Spatie permission names.

```php
namespace App\Abilities;

enum PostAbility: string
{
    case View = 'view posts';
    case Create = 'create posts';
    case Update = 'update posts';
    case Delete = 'delete posts';
}
```

Ability discovery scans files recursively and loads enum cases. It ignores files that do not resolve to classes or are not enums. Permission values must be globally unique across every discovered ability enum; duplicate values cause `rbac:reset` to fail with an `RbacException`.

`ResetPermissions` stores every discovered permission with `findOrCreate` for `auth.defaults.guard`. It does not remove old permission rows, and it does not automatically create the same abilities for other guards. Handle multi-guard permission provisioning deliberately, such as with a configured replacement job.

## Define roles

Create a role class with:

```bash
php artisan make:role EditorRole
```

Extend `BinaryCats\LaravelRbac\DefinedRole`, list its guards, and add one public method per guard. Each method returns backed enum cases and/or permission-name strings.

```php
namespace App\Roles;

use App\Abilities\PostAbility;
use BinaryCats\LaravelRbac\DefinedRole;

class EditorRole extends DefinedRole
{
    protected array $guards = ['web'];

    public function web(): array
    {
        return [
            PostAbility::View,
            PostAbility::Create,
            PostAbility::Update,
        ];
    }
}
```

Register every managed role in `config/rbac.php`:

```php
'roles' => [
    App\Roles\EditorRole::class,
],
```

The default role name is the class basename with a trailing `Role` removed, converted to a headline: `EditorRole` becomes `Editor`. Set the protected `$name` property when the database role name must differ. A guard listed in `$guards` must have a matching public method, and the method must return the permissions for that guard.

During synchronization, the package calls Spatie's `findOrCreate($name, $guard)` and `syncPermissions(...)` for each declared guard. Treat these PHP definitions as the source of truth for roles managed by this package; do not manually maintain their role-permission assignments in the database.

## Synchronize and authorize

Run the synchronization after changing abilities, role definitions, or relevant configuration:

```bash
php artisan rbac:reset
```

This uses Spatie's normal permission and role models, so use standard Spatie/Laravel authorization APIs in application code. Pass enum cases where the installed Spatie version supports enum permissions, or use their backed string values when an API requires a string:

```php
$user->can(PostAbility::Update->value);
// or, where enum support is available:
$user->can(PostAbility::Update);
```

## Safe change workflow

When asked to add or change access control:

1. Find the existing ability enum for the domain; add a case there rather than inventing overlapping permission strings.
2. Add the ability to the appropriate guard method(s) of the relevant `DefinedRole` classes.
3. Register a new role class in `rbac.roles` if it should be synchronized.
4. Run `php artisan rbac:reset` in an environment with the permission tables migrated.
5. Add or update tests for the declared permissions and resulting Spatie assignments.

For package development, run `composer test`; use `composer lint` when PHPStan is available in the project. Keep public behavior documented in the package README and preserve backwards compatibility for configuration keys, commands, stubs, and exposed classes.
