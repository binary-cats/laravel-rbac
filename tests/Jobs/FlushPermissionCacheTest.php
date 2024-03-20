<?php

namespace BinaryCats\LaravelRbac\Tests\Jobs;

use BinaryCats\LaravelRbac\Jobs\FlushPermissionCache;
use BinaryCats\LaravelRbac\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\PermissionRegistrar;

class FlushPermissionCacheTest extends TestCase
{
    #[Test]
    public function it_will_flush_permission_cache()
    {
        $registrar = $this->mock(PermissionRegistrar::class, function (MockInterface $mock) {
            $mock->shouldReceive('forgetCachedPermissions')
                ->andReturn(true);
        });

        $job = new FlushPermissionCache();

        $job->handle($registrar);

        $this->assertEmpty(Cache::get(config('permission.cache.key')));
    }
}
