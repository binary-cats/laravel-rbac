<?php

namespace BinaryCats\LaravelRbac;

use BinaryCats\LaravelRbac\Commands\AbilityMakeCommand;
use BinaryCats\LaravelRbac\Commands\DefinedRoleMakeCommand;
use BinaryCats\LaravelRbac\Commands\RbacResetCommand;
use Illuminate\Contracts\Foundation\Application;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RbacServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('rbac')
            ->hasConfigFile()
            ->hasCommands([
                AbilityMakeCommand::class,
                DefinedRoleMakeCommand::class,
                RbacResetCommand::class,
            ]);
    }

    /**
     * @return void
     */
    public function packageRegistered()
    {
        $this->app->bind(Rbac::class, function(Application $app) {
            return new Rbac(
                abilitiesPath: $app['config']->get('rbac.path'),
                basePath: $app->basePath()
            );
        });
    }
}
