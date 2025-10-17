<?php

namespace BinaryCats\LaravelRbac\Tests\Actions;

use BinaryCats\LaravelRbac\Actions\StorePermission;
use BinaryCats\LaravelRbac\Actions\SyncDefinedRole;
use BinaryCats\LaravelRbac\Tests\Fixtures\Abilities\FooAbility;
use BinaryCats\LaravelRbac\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class SyncDefinedRoleTest extends TestCase
{
    #[Test]
    public function it_will_defer_syncing_defined_role_to_artisan(): void
    {
        StorePermission::run('bar', 'web');
        StorePermission::run(FooAbility::One, 'web');

        SyncDefinedRole::run('foo role', 'web', [
            'bar',
            FooAbility::One,
        ]);

        $this->assertDatabaseHas(config('permission.table_names.roles'), [
            'name'       => 'foo role',
            'guard_name' => 'web',
        ]);
    }

    #[Test]
    public function it_will_defer_syncing_defined_role_to_artisan_with_custom_guard(): void
    {
        StorePermission::run('bar', 'admin');
        StorePermission::run(FooAbility::One, 'admin');

        SyncDefinedRole::run('foo role', 'admin', [
            'bar',
            FooAbility::One,
        ]);

        $this->assertDatabaseHas(config('permission.table_names.roles'), [
            'name'       => 'foo role',
            'guard_name' => 'admin',
        ]);

        $role = app(config('permission.models.role'))->where([
            'name'       => 'foo role',
            'guard_name' => 'admin',
        ])->firstOrFail();

        $this->assertTrue($role->hasPermissionTo('bar', 'admin'));
        $this->assertTrue($role->hasPermissionTo(FooAbility::One, 'admin'));
    }

    #[Test]
    public function it_will_throw_an_exception_on_missing_permission(): void
    {
        $this->expectException(PermissionDoesNotExist::class);
        $this->expectExceptionMessage('There is no permission named `bar` for guard `web`');

        SyncDefinedRole::run('foo role', 'web', [
            'bar',
        ]);
    }
}
