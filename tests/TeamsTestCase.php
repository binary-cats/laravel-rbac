<?php

namespace BinaryCats\LaravelRbac\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\PreCondition;
use Spatie\Permission\PermissionRegistrar;

class TeamsTestCase extends TestCase
{
    #[PreCondition]
    public function prepareData(): void
    {
        config()->set('permission.teams', true);
        app(PermissionRegistrar::class)->initializeCache();
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('permission.teams', true);
        $app['config']->set('auth.providers.users.model', Fixtures\TeamUser::class);

        parent::defineEnvironment($app);

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        $app->make(PermissionRegistrar::class)->initializeCache();
    }
}
