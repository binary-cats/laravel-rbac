<?php

namespace BinaryCats\LaravelRbac\Tests;

use BinaryCats\LaravelRbac\RbacServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\CollectionMacros\CollectionMacroServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends Orchestra
{
    /**
     * Get the package providers fopr registrations
     * 
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            CollectionMacroServiceProvider::class,
            PermissionServiceProvider::class,
            RbacServiceProvider::class,
        ];
    }

    /**
     * Resolve application Console Kernel implementation.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function resolveApplicationConsoleKernel($app): void
    {
        $app->singleton(
            'Illuminate\Contracts\Console\Kernel',
            'Illuminate\Foundation\Console\Kernel'
        );
    }

    /**
     * Set up the environment
     */
    public function getEnvironmentSetUp($app): void
    {
        dd('second');
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $migration = include __DIR__.'/../vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub';
        $migration->up();
    }
}
