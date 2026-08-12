<?php

namespace BinaryCats\LaravelRbac\Tests\Commands;

use BinaryCats\LaravelRbac\Tests\Fixtures\RbacResetJob;
use BinaryCats\LaravelRbac\Tests\TeamsTestCase;
use Illuminate\Support\Facades\Bus;
use PHPUnit\Framework\Attributes\Test;

class RbacResetCommandForTeamsTest extends TeamsTestCase
{
    #[Test]
    public function it_will_dispatch_when_the_spatie_teams_schema_is_migrated(): void
    {
        Bus::fake();

        config()->set('rbac.jobs', [RbacResetJob::class]);

        $this->artisan('rbac:reset')
            ->assertSuccessful();

        Bus::assertDispatched(RbacResetJob::class);
    }
}
