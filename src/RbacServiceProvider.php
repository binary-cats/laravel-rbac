<?php

namespace BinaryCats\Rbac;

use BinaryCats\Rbac\Commands\AbilityMakeCommand;
use BinaryCats\Rbac\Commands\DefinedRoleMakeCommand;
use BinaryCats\Rbac\Commands\RbacResetCommand;
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
