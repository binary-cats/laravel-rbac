<?php

namespace BinaryCats\LaravelRbac\Actions;

use BackedEnum;
use Lorisleiva\Actions\Action;
use Spatie\Permission\Contracts\Permission;

class StorePermission extends Action
{
    public function __construct(
        protected readonly Permission $permission
    ) {
    }

    /**
     * Handle storing a permission
     */
    public function handle(BackedEnum|string $permission, string $guard): void
    {
        if ($permission instanceof BackedEnum) {
            $permission = $permission->value;    
        }

        $this->permission::findOrCreate($permission, $guard);
    }
}
