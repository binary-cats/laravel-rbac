<?php

namespace BinaryCats\LaravelRbac\Actions;

use BackedEnum;
use Illuminate\Support\Facades\Artisan;
use Lorisleiva\Actions\Action;

class StorePermission extends Action
{
    /**
     * @param \BackedEnum $permission
     * @param string      $guard
     *
     * @return void
     */
    public function handle(BackedEnum $permission, string $guard): void
    {
        Artisan::call('permission:create-permission', [
            'name'  => $permission->value,
            'guard' => $guard,
        ]);
    }
}
