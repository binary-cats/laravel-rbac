<?php

namespace BinaryCats\LaravelRbac\Tests\Teams;

use BinaryCats\LaravelRbac\Actions\StorePermission;
use BinaryCats\LaravelRbac\Actions\SyncDefinedRole;
use BinaryCats\LaravelRbac\Tests\Fixtures\Abilities\FooAbility;
use BinaryCats\LaravelRbac\Tests\Fixtures\TeamUser;
use BinaryCats\LaravelRbac\Tests\TeamsTestCase;
use PHPUnit\Framework\Attributes\Test;

class DefinedRoleAssignmentTest extends TeamsTestCase
{
    #[Test]
    public function it_assigns_a_global_defined_role_within_the_active_team(): void
    {
        StorePermission::run(FooAbility::One, 'web');
        SyncDefinedRole::run('editor', 'web', [FooAbility::One]);

        $user = TeamUser::query()->create();

        setPermissionsTeamId(10);
        $user->assignRole('editor');

        $this->assertDatabaseHas(config('permission.table_names.model_has_roles'), [
            'role_id' => 1,
            'model_id' => $user->getKey(),
            'model_type' => $user->getMorphClass(),
            'team_id' => 10,
        ]);
    }
}
