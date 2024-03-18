<?php

namespace BinaryCats\Rbac;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use BinaryCats\Rbac\Commands\RbacCommand;

class RbacServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('rbac')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_rbac_table')
            ->hasCommand(RbacCommand::class);
    }
}
