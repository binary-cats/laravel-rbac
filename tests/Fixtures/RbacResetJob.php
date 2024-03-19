<?php

namespace BinaryCats\Rbac\Tests\Fixtures;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class RbacResetJob
{
    use Queueable;
    use InteractsWithQueue;
    use Dispatchable;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // this is intentionally  empty;
    }
}
