<?php

namespace BinaryCats\LaravelRbac\Actions;

use BackedEnum;
use Lorisleiva\Actions\Action;
use Spatie\Permission\Contracts\Role;

class SyncDefinedRole extends Action
{
    public function __construct(
        protected readonly Role $role
    ) {
    }

    /**
     * Handle syncing a defined role.
     */
    public function handle(string $name, string $guard, array $permissions): void
    {
        $permissions = collect($permissions)
            ->map(fn ($permission) => match (true) {
                $permission instanceof BackedEnum => $permission->value,
                default => (string) $permission
            });

        $this->role::findOrCreate($name, $guard)
            ->syncPermissions($permissions);
    }
}
