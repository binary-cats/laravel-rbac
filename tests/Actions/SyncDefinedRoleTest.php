<?php

namespace BinaryCats\LaravelRbac\Tests\Actions;

use BinaryCats\LaravelRbac\Actions\StorePermission;
use BinaryCats\LaravelRbac\Actions\SyncDefinedRole;
use BinaryCats\LaravelRbac\Tests\Fixtures\Abilities\FooAbility;
use BinaryCats\LaravelRbac\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

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
            'this-permission-is-new-and-will-be-created',
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
        $this->assertTrue($role->hasPermissionTo('this-permission-is-new-and-will-be-created', 'admin'));
    }
}
