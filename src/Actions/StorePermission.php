<?php

namespace BinaryCats\LaravelRbac\Actions;

use BackedEnum;
use Lorisleiva\Actions\Action;
use Spatie\Permission\Contracts\Permission;

class StorePermission extends Action
{
    /**
     * Handle storing a permission
     */
    public function handle(BackedEnum $permission, string $guard): void
    {
        $this->model()::findOrCreate($permission->value, $guard);
    }

    /**
     * Resolve permission model
     */
    protected function model(): Permission
    {
        dd(config('permission'));
        return app(Permission::class);
    }
}
