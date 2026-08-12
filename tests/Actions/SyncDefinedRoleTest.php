<?php

namespace BinaryCats\LaravelRbac\Tests\Actions;

use BinaryCats\LaravelRbac\Actions\StorePermission;
use BinaryCats\LaravelRbac\Actions\SyncDefinedRole;
use BinaryCats\LaravelRbac\Tests\Fixtures\Abilities\FooAbility;
use BinaryCats\LaravelRbac\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\PreCondition;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class SyncDefinedRoleTest extends TestCase
{
    #[PreCondition]
    public function prepareData(): void
    {
        StorePermission::run('bar', 'web');
        StorePermission::run(FooAbility::One, 'web');
    }

    #[Test]
    public function it_will_sync_a_defined_role(): void
    {
        SyncDefinedRole::run('foo role', 'web', [
            'bar',
            FooAbility::One,
        ]);

        $this->assertDatabaseHas(config('permission.table_names.roles'), [
            'name'       => 'foo role',
            'guard_name' => 'web',
        ]);

        $role = app(config('permission.models.role'))->where([
            'name'       => 'foo role',
            'guard_name' => 'web',
        ])->firstOrFail();

        $this->assertTrue($role->hasPermissionTo('bar', 'web'));
        $this->assertTrue($role->hasPermissionTo(FooAbility::One, 'web'));
    }

    #[Test]
    public function it_will_remove_permissions_that_are_no_longer_defined(): void
    {
        SyncDefinedRole::run('foo role', 'web', ['bar', FooAbility::One]);
        SyncDefinedRole::run('foo role', 'web', ['bar']);

        $role = app(config('permission.models.role'))->where([
            'name'       => 'foo role',
            'guard_name' => 'web',
        ])->firstOrFail();

        $this->assertTrue($role->hasPermissionTo('bar', 'web'));
        $this->assertFalse($role->hasPermissionTo(FooAbility::One, 'web'));
    }

    #[Test]
    public function it_will_throw_an_exception_when_a_permission_does_not_exist_for_a_custom_guard(): void
    {
        StorePermission::run('bar', 'admin');
        StorePermission::run(FooAbility::One, 'admin');

        $this->expectException(PermissionDoesNotExist::class);

        SyncDefinedRole::run('foo role', 'admin', [
            'bar',
            FooAbility::One,
            'this-permission-is-new-and-will-be-created',
        ]);
    }
}
