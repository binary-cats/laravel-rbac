<?php

namespace BinaryCats\Rbac;

use BinaryCats\Rbac\Contracts\DefinedRole as DefinedRoleContract;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class DefinedRole implements DefinedRoleContract
{
    /** @var array|string[]  */
    protected array $guards = [];

    /**
     * Guess the name of the role
     *
     * @return string
     */
    public function name(): string
    {
        $reflection = new ReflectionClass($this);

        if ($reflection->hasProperty('name')) {
            return $reflection->getProperty('name');
        }

        return Str::of($reflection->getName())
            ->classBasename()
            ->replaceLast('Role', '')
            ->snake()
            ->headline();
    }

    public function handle(): void
    {
        foreach($this->guards() as $guard) {
            SyncDefinedRole::run($this->name(), $guard, $this->{$guard}());
        }
    }
}
