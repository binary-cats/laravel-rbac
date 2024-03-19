<?php

namespace BinaryCats\Rbac\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class RbacResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rbac:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset RBAC';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->databaseReady()) {
            $this->withProgressBar($this->jobs(), fn ($job) => $job->dispatch());
            $this->newLine();
            $this->info('RBAC Reset Complete');
        } else {
            $this->error('DB is not ready');
        }

        return self::SUCCESS;
    }

    /**
     * Create the jobs.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function jobs(): Collection
    {
        $value = config('rbac.jobs');

        return collect($value)
            ->map(fn ($job) => app()->make($job));
    }

    /**
     * True if the Database is prepared.
     *
     * @return bool
     */
    protected function databaseReady(): bool
    {
        $value = config('permission.table_names');

        return collect($value)
            ->validate(fn ($table) => Schema::hasTable($table));
    }
}
