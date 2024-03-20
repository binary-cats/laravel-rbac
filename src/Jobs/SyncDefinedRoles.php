<?php

namespace BinaryCats\LaravelRbac\Jobs;

use BinaryCats\LaravelRbac\Contracts\DefinedRole;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SyncDefinedRoles
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->definedRoles()
            ->map(fn (DefinedRole $role) => $role->handle());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function definedRoles(): Collection
    {
        $value = config('rbac.roles');

        return collect($value)
            ->map(fn (string $role) => app($role));
    }
}
