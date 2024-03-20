<?php

namespace BinaryCats\LaravelRbac\Tests\Jobs;

use BinaryCats\LaravelRbac\Actions\StorePermission;
use BinaryCats\LaravelRbac\DiscoverAbilities;
use BinaryCats\LaravelRbac\Jobs\ResetPermissions;
use BinaryCats\LaravelRbac\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use SplFileInfo;

class ResetPermissionsTest extends TestCase
{
    #[Test]
    public function it_will_create_all_permissions()
    {
        DiscoverAbilities::guessClassNamesUsing(function (SplFileInfo $file, $basePath) {
            return Str::of($file->getRealPath())
                ->replaceFirst($basePath, '')
                ->trim(DIRECTORY_SEPARATOR)
                ->after('/tests/')
                ->prepend('BinaryCats/LaravelRbac/Tests/')
                ->replaceLast('.php', '')
                ->replace(DIRECTORY_SEPARATOR, '\\')
                ->toString();
        });

        $this->app->singleton(StorePermission::class, fn () => StorePermission::mock());

        Config::set('auth.defaults.guard', 'web');
        Config::set('rbac.path', __DIR__.'/../Fixtures/Abilities');

        StorePermission::mock()
            ->expects('handle')
            ->times(2);

        ResetPermissions::dispatch();
    }
}
