<?php

namespace BinaryCats\LaravelRbac\Tests;

use BinaryCats\LaravelRbac\RbacServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use PHPUnit\Framework\Attributes\PreCondition;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends Orchestra
{
    protected bool $withTeams = false;

    #[PreCondition]
    public function prepareTestCase(): void
    {
        config()->set('permission.teams', $this->withTeams);
        app(PermissionRegistrar::class)->initializeCache();
    }

    /**
     * Get the package providers fopr registrations.
     *
     * @param Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            PermissionServiceProvider::class,
            RbacServiceProvider::class,
        ];
    }

    /**
     * Define the environment.
     */
    protected function defineEnvironment($app): void
    {
        tap($app['config'], function (Repository $config) {
            $config->set('database.default', 'sqlite');
            $config->set('database.connections.sqlite', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]);
            $config->set('permission.teams', $this->withTeams);

            if ($this->withTeams) {
                $config->set('auth.providers.users.model', Fixtures\TeamUser::class);
            }
        });

        $migration = include __DIR__.'/../vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub';
        $migration->up();

        if ($this->withTeams) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });

            $app->make(PermissionRegistrar::class)->initializeCache();
        }
    }
}
