<?php

namespace BinaryCats\LaravelRbac\Tests\Actions;

use BinaryCats\LaravelRbac\Actions\StorePermission;
use BinaryCats\LaravelRbac\Actions\SyncDefinedRole;
use BinaryCats\LaravelRbac\Jobs\SyncDefinedRoles;
use BinaryCats\LaravelRbac\Tests\Fixtures\Abilities\FooAbility;
use BinaryCats\LaravelRbac\Tests\Fixtures\FooRole;
use BinaryCats\LaravelRbac\Tests\TestCase;
use PHPUnit\Framework\Attributes\PreCondition;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Contracts\Role;

class SyncDefinedRoleForTeamsTest extends TestCase
{
    protected bool $withTeams = true;

    #[PreCondition]
    public function prepareData(): void
    {
        StorePermission::run(FooAbility::One, 'web');
    }

    #[Test]
    public function it_syncs_a_defined_role_globally_and_restores_the_active_team(): void
    {
        setPermissionsTeamId(10);

        SyncDefinedRole::run('editor', 'web', [FooAbility::One]);

        $this->assertDatabaseHas(config('permission.table_names.roles'), [
            'name'       => 'editor',
            'guard_name' => 'web',
            'team_id'    => null,
        ]);
        $this->assertSame(10, getPermissionsTeamId());
    }

    #[Test]
    public function it_does_not_change_a_tenant_custom_role_when_syncing_defined_roles(): void
    {
        setPermissionsTeamId(10);
        $customRole = app(Role::class)::findOrCreate('tenant manager', 'web');
        $customRole->syncPermissions([FooAbility::One]);

        config()->set('rbac.roles', [FooRole::class]);

        SyncDefinedRoles::dispatch();

        $this->assertDatabaseHas(config('permission.table_names.roles'), [
            'name'       => 'tenant manager',
            'guard_name' => 'web',
            'team_id'    => 10,
        ]);
        $this->assertTrue($customRole->fresh()->hasPermissionTo(FooAbility::One, 'web'));
    }
}
