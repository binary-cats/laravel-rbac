<?php

namespace BinaryCats\Rbac\Tests\Jobs;

use BinaryCats\Rbac\Actions\StorePermission;
use BinaryCats\Rbac\DiscoverAbilities;
use BinaryCats\Rbac\Jobs\ResetPermissions;
use BinaryCats\Rbac\Tests\Fixtures\Abilities\FooAbility;
use BinaryCats\Rbac\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use SplFileInfo;

class ResetPermissionsTest extends TestCase
{
    #[Test]
    public function it_will_create_all_permissions()
    {
        Config::set('auth.defaults.guard', 'web');
        Config::set('rbac.path', __DIR__.'/../Fixtures/Abilities');

        StorePermission::mock()
            ->expects('handle')
            ->times(2);

        ResetPermissions::dispatch();
    }
}
