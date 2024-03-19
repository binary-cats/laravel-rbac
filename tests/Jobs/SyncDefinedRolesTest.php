<?php

namespace BinaryCats\Rbac\Tests\Jobs;

use BinaryCats\Rbac\Actions\SyncDefinedRole;
use BinaryCats\Rbac\Jobs\SyncDefinedRoles;
use BinaryCats\Rbac\Tests\Fixtures\FooRole;
use BinaryCats\Rbac\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SyncDefinedRolesTest extends TestCase
{
    #[Test]
    public function it_will_sync_defined_roles_and_their_abilities(): void
    {
        config()->set('rbac.roles', [
            FooRole::class,
        ]);

        $this->app->singleton(SyncDefinedRole::class, fn() => SyncDefinedRole::mock());

        SyncDefinedRole::mock()
            ->expects('handle')
            ->once()
            ->with(
                'Foo',
                'web',
                app(FooRole::class)->web()
            );

        SyncDefinedRoles::dispatch();
    }
}
