<?php

namespace BinaryCats\LaravelRbac\Tests\Commands;

use BinaryCats\LaravelRbac\Tests\Fixtures\RbacResetJob;
use BinaryCats\LaravelRbac\Tests\TestCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;

class RbacResetCommandTest extends TestCase
{
    #[Before]
    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake();
    }

    #[Test]
    public function it_will_dispatch_all_configured_commands()
    {
        $this->app['config']->set([
            'rbac.jobs' => [RbacResetJob::class],
        ]);

        $this->artisan('rbac:reset')
            ->assertSuccessful();

        Bus::assertDispatched(RbacResetJob::class);
    }

    #[Test]
    public function it_will_not_dispatch_if_not_migrated(): void
    {
        $this->app['config']->set([
            'permission.table_names.permissions' => 'foo',
        ]);

        Schema::expects('hasTable')
            ->with('foo')
            ->andReturnFalse();

        $this->artisan('rbac:reset')
            ->assertExitCode(Command::INVALID);

        Bus::assertNotDispatched(RbacResetJob::class);
    }
}
