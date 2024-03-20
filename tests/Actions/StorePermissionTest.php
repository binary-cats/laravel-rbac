<?php

namespace BinaryCats\LaravelRbac\Tests\Actions;

use BinaryCats\LaravelRbac\Actions\StorePermission;
use BinaryCats\LaravelRbac\Tests\Fixtures\Abilities\FooAbility;
use BinaryCats\LaravelRbac\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class StorePermissionTest extends TestCase
{
    #[Test]
    public function it_will_handle_creating_permission(): void
    {
        Artisan::expects('call')
            ->once()
            ->with(
                'permission:create-permission',
                [
                    'name'  => 'una',
                    'guard' => 'web',
                ]
            );

        StorePermission::run(FooAbility::One, 'web');
    }
}
