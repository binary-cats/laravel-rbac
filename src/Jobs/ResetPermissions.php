<?php

namespace BinaryCats\LaravelRbac\Jobs;

use BackedEnum;
use BinaryCats\LaravelRbac\Actions\StorePermission;
use BinaryCats\LaravelRbac\DiscoverAbilities;
use BinaryCats\LaravelRbac\Facades\Rbac;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ResetPermissions
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected string $guard;

    /**
     * @param string|null $guard
     */
    public function __construct(string $guard = null)
    {
        $this->guard = $guard ?? config('auth.defaults.guard');
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->permissions()
            ->each(fn (BackedEnum $ability) => StorePermission::run($ability, $this->guard));
    }

    /**
     * @return \Illuminate\Support\Collection<int, \BackedEnum>
     */
    protected function permissions(): Collection
    {
        return Rbac::abilities();
    }
}
