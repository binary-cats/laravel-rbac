<?php

namespace BinaryCats\Rbac;

use BinaryCats\Rbac\Commands\AbilityMakeCommand;
use BinaryCats\Rbac\Commands\DefinedRoleMakeCommand;
use BinaryCats\Rbac\Commands\RbacResetCommand;
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
}
