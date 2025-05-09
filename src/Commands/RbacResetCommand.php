<?php

namespace BinaryCats\LaravelRbac\Commands;

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
     */
    public function handle(): int
    {
        if (! $this->databaseReady()) {
            $this->error('DB is not ready. Please run migrations.');

            return self::INVALID;
        }

        $this->jobs()->each(function(string $job) {
            $this->components->task(
                $job,
                fn() => $this->laravel->make($job)->dispatchSync()
            );
        });

        return self::SUCCESS;
    }

    /**
     * Create the jobs.
     */
    protected function jobs(): Collection
    {
        $value = config('rbac.jobs');

        return collect($value);
    }

    /**
     * True if the Database is prepared.
     */
    protected function databaseReady(): bool
    {
        $value = config('permission.table_names');

        return collect($value)
            ->validate(fn ($table) => Schema::hasTable($table));
    }
}
