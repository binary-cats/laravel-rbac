<?php

namespace BinaryCats\Rbac\Commands;

use Illuminate\Console\Command;

class RbacCommand extends Command
{
    public $signature = 'rbac';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
