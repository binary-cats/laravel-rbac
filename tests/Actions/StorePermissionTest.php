<?php

namespace BinaryCats\LaravelRbac\Tests\Actions;

use BinaryCats\LaravelRbac\Actions\StorePermission;
use BinaryCats\LaravelRbac\Tests\Fixtures\Abilities\FooAbility;
use BinaryCats\LaravelRbac\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class StorePermissionTest extends TestCase
{
    #[Test]
    public function it_will_handle_creating_permission(): void
    {
        StorePermission::run(FooAbility::One, 'web');

        $this->assertDatabaseHas(config('permission.table_names.permissions'), [
            'name'       => 'una',
            'guard_name' => 'web',
        ]);
    }
}
