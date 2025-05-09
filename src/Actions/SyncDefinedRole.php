<?php

namespace BinaryCats\LaravelRbac\Actions;

use BackedEnum;
use Lorisleiva\Actions\Action;
use Spatie\Permission\Contracts\Role;

class SyncDefinedRole extends Action
{
    /**
     * Handle syncing a defined role
     */
    public function handle(string $name, string $guard, array $permissions): void
    {
        $permissions = collect($permissions)
            ->map(fn ($permission) => match (true) {
                $permission instanceof BackedEnum => $permission->value,
                default                           => (string) $permission
            });

        $this->model()
            ->findOrCreate($name, $guard)
            ->syncPermissions($permissions);
    }

    /**
     * Resolve role model
     */
    protected function model(): Role
    {
        return app(Role::class);
    }
}
