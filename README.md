[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

![](https://banners.beyondco.de/Laravel%20RBAC.png?theme=light&packageManager=composer+require&packageName=binary-cats%2Flaravel-rbac&pattern=architect&style=style_1&description=Manage+your+spatie%2Flaravel-permission+lists+with+well-defined+roles&md=1&showWatermark=1&fontSize=100px&images=lock-closed)

# Laravel RBAC

[![Latest Version on Packagist](https://img.shields.io/packagist/v/binary-cats/laravel-rbac.svg?style=flat-square)](https://packagist.org/packages/binary-cats/laravel-rbac)
[![run-tests](https://img.shields.io/github/actions/workflow/status/binary-cats/laravel-rbac/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/binary-cats/laravel-rbac/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://github.styleci.io/repos/773171043/shield?branch=main)](https://github.com/binary-cats/laravel-rbac/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)

Enhance Laravel 11 with opinionated extension for [spatie/laravel-permissions](https://spatie.be/docs/laravel-permission/v6/introduction).
Before your permission list grows and maintenance becomes an issue, this package offers simple way of defining roles and their permissions.  

## Installation

You can install the package via composer:

```bash
composer require binary-cats/laravel-rbac
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="rbac-config"
```

This is the contents of the published config file:

```php
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
        \BinaryCats\LaravelRbac\Jobs\FlushPermissionCache::class,
        \BinaryCats\LaravelRbac\Jobs\ResetPermissions::class,
        \BinaryCats\LaravelRbac\Jobs\SyncDefinedRoles::class,
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
```

You can publish the stub files with:

```bash
php artisan vendor:publish --tag="rbac-stubs"
```

## Usage

```bash
php artisan rbac:reset
```

In a simple setup we usually have two basic parts of an RBAC: a permission and a role. 
Permissions are usually grouped by functional or business logic domain and a Role encapsulates them for a specific guard.

1. [Create Abilities](#abilities)
2. [Define Roles](#defined-roles)
3. [Connect the dots](#connect-the-dots)

### Abilities

To avoid collision with `spatie/laravel-permission` we are going to use `BackedEnum` Ability enums to hold out enumerated permissions:
You can read more on using `enums` as permissions at the [official docs](https://spatie.be/docs/laravel-permission/v6/basic-usage/enums). 

To create an Ability: 

```bash 
php artisan make:ability PostAbility
``` 

This will generate a `PostAbility` in `App\Abilities`:

```php
namespace App\Abilities;

enum PostAbility: string
{
    case ViewPost = 'view post';
    case CreatePost = 'create post';
    case UpdatePost = 'update post';
    case DeletePost = 'delete post';
}
```
Default stub contains fairly standard CRUD enumeration, generated using the name of the ability. Feel free to publish the stubs and adjsut as needed. 


### Defined Roles

As the name suggests, a `DefinedRole` offers a mechanism to simplify the definition of all permissions needed for a given role. 
To create an `EditorRole` run:

```bash 
php artisan make:role EditorRole
```

This will generate an `EditorRole` within `App\Roles`:

```php
use BinaryCats\LaravelRbac\DefinedRole;

class EditorRole extends DefinedRole
{
    /** @var array|string[]  */
    protected array $guards = [
        'web'
    ];

    /**
     * List of enumerated permissions for the `web` guard
     *
     * @return array
     */
    public function web(): array
    {
        return [];
    }
}
```

This class contains a (now testable!) configuration definition for the role and its `web` guard. Pretty neat! 
We can now adjust it like so:

```php
namespace App\Roles;

use App\Abilities\PostAbility;
use BinaryCats\LaravelRbac\DefinedRole;

class EditorRole extends DefinedRole
{
    /** @var array|string[]  */
    protected array $guards = [
        'web'
    ];

    /**
     * List of enumerated permissions for the `web` guard
     *
     * @return array
     */
    public function web(): array
    {
        return [
            PostAbility::CreatePost,
            PostAbility::UpdatePost,
            PostAbility::ViewPost,
        ];
    }
}
```
Now you are confident a specific role has specific permissions!

### Connect the dots

Now that we have the abilities and roles, simply register role with `rbac.php` config:

```php
    'roles' => [
        \App\Roles\EditorRole::class,
        ...
    ],
```

When you run `rbac:reset` next time, your RBAC will be reset automatically.

## Integration

I suggest adding the script to `post-autoload-dump` of your `composer.json` to make sure the RBAC is reset on every composer dump:

```json
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan rbac:reset"
        ],
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email cyrill.kalita@gmail.com instead of using issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

## Credits

- [Cyrill N Kalita](https://github.com/cyrillkalita)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
