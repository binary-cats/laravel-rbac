<?php

namespace BinaryCats\LaravelRbac\Actions;

use BackedEnum;
use Illuminate\Support\Facades\Artisan;
use Lorisleiva\Actions\Action;
use Spatie\Permission\Commands\CreateRoleCommand;

class SyncDefinedRole extends Action
{
    /**
     * Handle syncing a defined role.
     */
    public function handle(string $name, string $guard, array $permissions): void
    {
        $permissions = collect($permissions)
            ->map(fn ($permission): string => match (true) {
                $permission instanceof BackedEnum => $permission->value,
                default                           => (string) $permission
            })->implode('|');

        Artisan::call(CreateRoleCommand::class, [
            'name'        => $name,
            'guard'       => $guard,
            'permissions' => $permissions,
        ]);
    }
}
