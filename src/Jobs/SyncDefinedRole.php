<?php

namespace BinaryCats\Rbac\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;

class SyncDefinedRole
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $name
     * @param string $guard
     * @param array $permissions
     */
    public function __construct(
        protected string $name,
        protected string $guard,
        protected array $permissions
    ) {
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        Artisan::call('permission:create-role', [
            'name' => $this->name,
            'guard' => $this->guard,
            'permissions' => $this->permissions(),
        ]);
    }

    /**
     * @return string
     */
    protected function permissions(): string
    {
        return implode('|', $this->permissions);
    }
}
