<?php

namespace BinaryCats\LaravelRbac\Tests\Actions;

use BinaryCats\LaravelRbac\Actions\SyncDefinedRole;
use BinaryCats\LaravelRbac\Tests\Fixtures\Abilities\FooAbility;
use BinaryCats\LaravelRbac\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class SyncDefinedRoleTest extends TestCase
{
    #[Test]
    public function it_will_defer_syncing_defined_role_to_artisan(): void
    {
        Artisan::expects('call')
            ->once()
            ->with(
                'permission:create-role',
                [
                    'name'        => 'foo role',
                    'guard'       => 'web',
                    'permissions' => 'bar|una',
                ]
            );

        SyncDefinedRole::run('foo role', 'web', [
            'bar',
            FooAbility::One,
        ]);
    }
}
