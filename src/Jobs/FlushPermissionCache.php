<?php

namespace BinaryCats\Rbac\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Permission\PermissionRegistrar;

class FlushPermissionCache
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param \Spatie\Permission\PermissionRegistrar $registrar
     * @return void
     */
    public function handle(PermissionRegistrar $registrar): void
    {
        $registrar->forgetCachedPermissions();
    }
}
